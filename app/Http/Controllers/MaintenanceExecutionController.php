<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MaintenancePlan;
use App\Models\MaintenanceExecution;
use App\Models\MaintenanceExecutionAnswer;
use App\Models\MaintenanceExecutionPhoto;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;

class MaintenanceExecutionController extends Controller
{
    protected ImageCompressionService $compressionService;

    // Predefined Operator List to ensure consistency (Version 1)
    protected array $operators = [
        'R. Miller',
        'S. Chen',
        'R. Thompson',
        'M. Fadil',
        'A. Hidayat',
        'B. Setiawan'
    ];

    public function __construct(ImageCompressionService $compressionService)
    {
        $this->compressionService = $compressionService;
    }

    /**
     * QR Entry point scanned from physical machine barcode.
     * Identifies the oldest pending/scheduled maintenance plan and redirects to execution.
     */
    public function qrEntry(string $machineCode)
    {
        $machine = Machine::where('code', $machineCode)->first();
        if (!$machine) {
            abort(404, 'Mesin tidak ditemukan.');
        }

        // Find oldest active pending plan (status: scheduled, approved, waiting_approval, or draft)
        $plan = MaintenancePlan::where('machine_id', $machine->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('scheduled_date', 'asc')
            ->first();

        if (!$plan) {
            return redirect()->route('machines.show', $machine->id)
                ->with('info', 'Tidak ada rencana perawatan aktif yang terjadwal untuk mesin ini.');
        }

        return redirect()->route('planning.execute', $plan->id);
    }

    /**
     * Show mobile checklist execution view.
     */
    public function create(MaintenancePlan $plan)
    {
        if ($plan->status === 'completed') {
            return redirect()->route('planning.show', $plan->id)
                ->with('warning', 'Pemeriksaan perawatan untuk rencana ini sudah diselesaikan.');
        }

        $plan->load(['machine', 'maintenanceTemplate.checklists']);
        $operators = $this->operators;

        return view('planning.execute', compact('plan', 'operators'));
    }

    /**
     * Store submitted execution checklist and answers.
     */
    public function store(Request $request, MaintenancePlan $plan)
    {
        if ($plan->status === 'completed') {
            return redirect()->route('planning.show', $plan->id)
                ->with('error', 'Rencana perawatan ini sudah selesai.');
        }

        // Create validator
        $validator = Validator::make($request->all(), [
            'operator_name' => 'required|string',
            'started_at' => 'required|date_format:Y-m-d H:i:s',
            'photo' => 'required|image|max:10240', // Max 10MB upload
            'notes' => 'nullable|string',
        ]);

        // Evaluate answers and apply conditional validation rules
        $validator->after(function ($validator) use ($request, $plan) {
            $answers = $request->input('answers', []);
            $checklistItems = $plan->maintenanceTemplate->checklists;

            foreach ($checklistItems as $item) {
                $ans = $answers[$item->id] ?? null;
                if (!$ans || !isset($ans['score'])) {
                    $validator->errors()->add("answers.{$item->id}.score", "Nilai pemeriksaan untuk '{$item->title}' wajib diisi.");
                    continue;
                }

                $score = (int) $ans['score'];
                if ($score < 1 || $score > 5) {
                    $validator->errors()->add("answers.{$item->id}.score", "Nilai harus di antara 1 dan 5.");
                }

                // Conditional validation: Score 1 requires remarks
                if ($score === 1 && empty(trim($ans['remarks'] ?? ''))) {
                    $validator->errors()->add("answers.{$item->id}.remarks", "Catatan kerusakan wajib diisi jika nilai pemeriksaan bernilai 1.");
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. Process and compress image using ImageCompressionService
            $photoPath = $this->compressionService->compressAndStore($request->file('photo'));

            // 2. Calculate average score
            $answers = $request->input('answers', []);
            $totalScore = 0;
            $count = count($answers);
            foreach ($answers as $ans) {
                $totalScore += (int) $ans['score'];
            }
            $overallScore = $count > 0 ? $totalScore / $count : 5.00;

            // 3. Create execution log (status: waiting_review to preserve lifecycle split)
            $execution = MaintenanceExecution::create([
                'maintenance_plan_id' => $plan->id,
                'machine_id' => $plan->machine_id,
                'operator_name' => $request->input('operator_name'),
                'started_at' => $request->input('started_at'),
                'completed_at' => now(),
                'overall_score' => $overallScore,
                'notes' => $request->input('notes'),
                'status' => 'waiting_review',
            ]);

            // 4. Create photo record
            MaintenanceExecutionPhoto::create([
                'execution_id' => $execution->id,
                'type' => 'general',
                'photo_path' => $photoPath,
            ]);

            // 5. Store checklist answers
            foreach ($answers as $itemId => $ansData) {
                MaintenanceExecutionAnswer::create([
                    'execution_id' => $execution->id,
                    'checklist_item_id' => $itemId,
                    'score' => (int) $ansData['score'],
                    'remarks' => $ansData['remarks'] ?? null,
                ]);
            }

            // 6. Complete the plan so Planning Board updates immediately
            $plan->update(['status' => 'completed']);

            DB::commit();

            return redirect()->route('planning.show', $plan->id)
                ->with('success', 'Laporan perawatan berhasil diserahkan dan disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan laporan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Render printable Work Order briefing sheet.
     */
    public function print(MaintenancePlan $plan)
    {
        $plan->load([
            'machine.documents',
            'maintenanceTemplate.checklists',
            'maintenanceTemplate.spareparts'
        ]);

        // Find previous execution notes for technician briefing
        $previousExecution = MaintenanceExecution::where('machine_id', $plan->machine_id)
            ->orderBy('completed_at', 'desc')
            ->first();

        // Generate offline SVG QR code
        $qrOptions = new QROptions([
            'outputBase64' => true,
            'scale' => 5,
            'eccLevel' => EccLevel::L,
        ]);
        $executionUrl = route('planning.execute', $plan->id);
        $qrCodeImage = (new QRCode($qrOptions))->render($executionUrl);

        return view('planning.print', compact('plan', 'previousExecution', 'qrCodeImage'));
    }
}
