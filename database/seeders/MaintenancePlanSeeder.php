<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\MaintenanceTemplate;
use App\Models\MaintenanceTemplateChecklist;
use App\Models\MaintenanceTemplateSparepart;
use App\Models\MaintenancePlan;
use Illuminate\Database\Seeder;

class MaintenancePlanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Maintenance Templates (Paket Perawatan)
        
        // Template 1: CNC Milling
        $tplMilling = MaintenanceTemplate::create([
            'name' => 'Servis Bulanan CNC Milling',
            'description' => 'Prosedur pemeliharaan bulanan standar untuk pusat milling CNC. Berfokus pada keandalan spindle, tekanan coolant, dan sistem lubrikasi sumbu.',
            'machine_category' => 'Milling Machine',
            'maintenance_type' => 'Monthly',
            'estimated_duration' => 120,
            'is_active' => true,
        ]);

        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplMilling->id,
            'sequence' => 1,
            'title' => 'Kalibrasi Tekanan Cairan Pendingin (Coolant)',
            'description' => 'Uji tekanan pompa coolant dan bersihkan filter nozzle.',
            'is_required' => true,
        ]);
        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplMilling->id,
            'sequence' => 2,
            'title' => 'Pemeriksaan Titik Lubrikasi Poros (Axis)',
            'description' => 'Lumasi semua guide way linear pada sumbu X, Y, Z.',
            'is_required' => true,
        ]);
        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplMilling->id,
            'sequence' => 3,
            'title' => 'Pembersihan Serpihan Chip Conveyor',
            'description' => 'Bersihkan penumpukan serpihan logam di area conveyor bawah.',
            'is_required' => false,
        ]);
        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplMilling->id,
            'sequence' => 4,
            'title' => 'Pengukuran Spindle Runout',
            'description' => 'Uji deviasi toleransi radial spindle menggunakan dial indicator.',
            'is_required' => true,
        ]);

        MaintenanceTemplateSparepart::create([
            'maintenance_template_id' => $tplMilling->id,
            'warehouse_item_code' => 'BRG-6204',
            'quantity' => 2,
        ]);
        MaintenanceTemplateSparepart::create([
            'maintenance_template_id' => $tplMilling->id,
            'warehouse_item_code' => 'SEAL-TC-40',
            'quantity' => 1,
        ]);


        // Template 2: CNC Lathe
        $tplLathe = MaintenanceTemplate::create([
            'name' => 'Penyelarasan Presisi Bubut',
            'description' => 'Paket penyelarasan sumbu presisi dan uji runout chuck bubut.',
            'machine_category' => 'Lathe Machine',
            'maintenance_type' => 'Quarterly',
            'estimated_duration' => 150,
            'is_active' => true,
        ]);

        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplLathe->id,
            'sequence' => 1,
            'title' => 'Pemeriksaan Penjajaran Chuck',
            'description' => 'Lakukan kalibrasi kelurusan chuck terhadap sumbu headstock.',
            'is_required' => true,
        ]);
        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplLathe->id,
            'sequence' => 2,
            'title' => 'Lubrikasi Turret Alat',
            'description' => 'Periksa level oli indeks turret dan tambahkan gemuk jika diperlukan.',
            'is_required' => true,
        ]);

        MaintenanceTemplateSparepart::create([
            'maintenance_template_id' => $tplLathe->id,
            'warehouse_item_code' => 'BRG-6204',
            'quantity' => 1,
        ]);
        MaintenanceTemplateSparepart::create([
            'maintenance_template_id' => $tplLathe->id,
            'warehouse_item_code' => 'SEAL-TC-40',
            'quantity' => 1,
        ]);


        // Template 3: Robotic Welder
        $tplRobot = MaintenanceTemplate::create([
            'name' => 'Pemeriksaan Mingguan Robot Las',
            'description' => 'Pemeriksaan integritas mekanis lengan robot dan uji parameter kelistrikan.',
            'machine_category' => 'Industrial Robot',
            'maintenance_type' => 'Weekly',
            'estimated_duration' => 90,
            'is_active' => true,
        ]);

        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplRobot->id,
            'sequence' => 1,
            'title' => 'Uji Jangkauan Gerak & Rem Servo',
            'description' => 'Jalankan routine gerak penuh sumbu 1-6 dan uji respon pengereman darurat.',
            'is_required' => true,
        ]);
        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplRobot->id,
            'sequence' => 2,
            'title' => 'Pembersihan Nozzle Las & Kalibrasi Gas',
            'description' => 'Bersihkan slag dari tip nozzle las dan verifikasi laju aliran gas pelindung.',
            'is_required' => true,
        ]);
        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplRobot->id,
            'sequence' => 3,
            'title' => 'Pemeriksaan Kabinet Kontrol Elektronik',
            'description' => 'Bersihkan debu dari kipas filter pendingin dan periksa kelonggaran kabel.',
            'is_required' => true,
        ]);

        MaintenanceTemplateSparepart::create([
            'maintenance_template_id' => $tplRobot->id,
            'warehouse_item_code' => 'RLY-24V',
            'quantity' => 1,
        ]);


        // Template 4: Pump
        $tplPump = MaintenanceTemplate::create([
            'name' => 'Kalibrasi Aliran Pompa',
            'description' => 'Uji tekanan hidrolik dan analisis kontaminasi oli.',
            'machine_category' => 'Pump',
            'maintenance_type' => 'Semi Annual',
            'estimated_duration' => 60,
            'is_active' => true,
        ]);

        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplPump->id,
            'sequence' => 1,
            'title' => 'Pengukuran Tekanan Pompa Hidrolik',
            'description' => 'Catat tekanan keluaran pada katup relief.',
            'is_required' => true,
        ]);
        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplPump->id,
            'sequence' => 2,
            'title' => 'Penggantian Oli Hidrolik Parsial',
            'description' => 'Kuras 10 liter oli lama dan tambahkan oli hidrolik baru.',
            'is_required' => true,
        ]);

        MaintenanceTemplateSparepart::create([
            'maintenance_template_id' => $tplPump->id,
            'warehouse_item_code' => 'HYD-OIL-46',
            'quantity' => 2,
        ]);


        // Template 5: Drill Press
        $tplDrill = MaintenanceTemplate::create([
            'name' => 'Perawatan Umum Mesin Bor',
            'description' => 'Pemeriksaan kelonggaran bearing spindel bor dan ketegangan sabuk motor.',
            'machine_category' => 'Drilling Machine',
            'maintenance_type' => 'Annual',
            'estimated_duration' => 45,
            'is_active' => true,
        ]);

        MaintenanceTemplateChecklist::create([
            'maintenance_template_id' => $tplDrill->id,
            'sequence' => 1,
            'title' => 'Penjajaran Sabuk (Belt Alignment) & Ketegangan',
            'description' => 'Sesuaikan ketegangan sabuk V-belt agar tidak selip.',
            'is_required' => true,
        ]);

        MaintenanceTemplateSparepart::create([
            'maintenance_template_id' => $tplDrill->id,
            'warehouse_item_code' => 'VBLT-A42',
            'quantity' => 1,
        ]);


        // 2. Seed Maintenance Plans (Rencana Perawatan)
        
        $cnc08 = Machine::where('code', 'CNC-08')->first();
        $cnc04 = Machine::where('code', 'CNC-04')->first();
        $arm12 = Machine::where('code', 'ARM-12')->first();
        $pmp08 = Machine::where('code', 'PMP-08')->first();
        $drl19 = Machine::where('code', 'DRL-19')->first();

        // Plan 1: Scheduled / Blocked (for CNC-08)
        if ($cnc08) {
            MaintenancePlan::create([
                'machine_id' => $cnc08->id,
                'maintenance_template_id' => $tplMilling->id,
                'scheduled_date' => now(), // today
                'assigned_technician' => 'R. Miller',
                'priority' => 'critical',
                'status' => 'scheduled',
                'generation_source' => 'Manual',
                'notes' => 'Mesin mati akibat spindle overheating. Membutuhkan suku cadang pengganti yang saat ini kosong di WMS.',
            ]);
        }

        // Plan 2: Blocked (Terblokir) due to machine down (breakdown) and missing sparepart (SEAL-TC-40) and unassigned technician
        if ($cnc04) {
            MaintenancePlan::create([
                'machine_id' => $cnc04->id,
                'maintenance_template_id' => $tplLathe->id,
                'scheduled_date' => now(), // today
                'assigned_technician' => null,
                'priority' => 'high',
                'status' => 'draft',
                'generation_source' => 'Manual',
                'notes' => 'Penyelarasan bubut presisi tertunda karena teknisi belum ditentukan dan suku cadang silinder seal kosong.',
            ]);
        }

        // Plan 3: Almost Ready (Hampir Siap) due to missing documents (ARM-12 has no manual_book)
        if ($arm12) {
            MaintenancePlan::create([
                'machine_id' => $arm12->id,
                'maintenance_template_id' => $tplRobot->id,
                'scheduled_date' => now(), // today
                'assigned_technician' => 'S. Chen',
                'priority' => 'medium',
                'status' => 'approved',
                'generation_source' => 'Manual',
                'notes' => 'Pemeriksaan mingguan lengan robotik Fanuc. Suku cadang tersedia di WMS.',
            ]);
        }

        // Plan 4: Ready (Siap) - DRL-19 is running (ready), V-Belt in stock (8), has manual_book, tech assigned
        if ($drl19) {
            MaintenancePlan::create([
                'machine_id' => $drl19->id,
                'maintenance_template_id' => $tplDrill->id,
                'scheduled_date' => now(), // today
                'assigned_technician' => 'R. Thompson',
                'priority' => 'low',
                'status' => 'approved',
                'generation_source' => 'Manual',
                'notes' => 'Penyelarasan ketegangan belt tahunan mesin bor radial.',
            ]);
        }

        // Plan 5: Almost Ready (Hampir Siap) due to unassigned technician (PMP-08 is idle, hyd oil is 2 - stock ok, no manual book)
        if ($pmp08) {
            MaintenancePlan::create([
                'machine_id' => $pmp08->id,
                'maintenance_template_id' => $tplPump->id,
                'scheduled_date' => now()->addDays(3),
                'assigned_technician' => null,
                'priority' => 'medium',
                'status' => 'waiting_approval',
                'generation_source' => 'Generated',
                'notes' => 'Kalibrasi aliran hidrolik teratur. Dibuat secara otomatis oleh Reliability Engine.',
            ]);
        }

        // Plan 6: In Progress (Sedang Berjalan)
        if ($pmp08) {
            MaintenancePlan::create([
                'machine_id' => $pmp08->id,
                'maintenance_template_id' => $tplPump->id,
                'scheduled_date' => now(), // today
                'assigned_technician' => 'M. Fadil',
                'priority' => 'medium',
                'status' => 'in_progress',
                'generation_source' => 'Manual',
                'notes' => 'Kalibrasi aliran hidrolik. Teknisi sedang berada di area utilitas.',
            ]);
        }

        // Plan 7: Completed / Waiting Review (PMP-08)
        if ($pmp08) {
            $plan7 = MaintenancePlan::create([
                'machine_id' => $pmp08->id,
                'maintenance_template_id' => $tplPump->id,
                'scheduled_date' => now(), // today
                'assigned_technician' => 'R. Miller',
                'priority' => 'medium',
                'status' => 'completed',
                'generation_source' => 'Manual',
                'notes' => 'Kalibrasi aliran hidrolik selesai dilakukan.',
            ]);

            // Create execution log for Plan 7 (waiting review)
            $exec7 = \App\Models\MaintenanceExecution::create([
                'maintenance_plan_id' => $plan7->id,
                'machine_id' => $pmp08->id,
                'operator_name' => 'R. Miller',
                'started_at' => now()->subMinutes(95),
                'completed_at' => now()->subMinutes(60),
                'overall_score' => 4.25,
                'notes' => 'Kalibrasi aliran hidrolik selesai, tekanan diatur kembali ke standar operasional.',
                'status' => 'waiting_review',
            ]);

            // Create checklist answers
            $checklists = $tplPump->checklists;
            $scores = [1 => 4, 2 => 5, 3 => 3, 4 => 5];
            foreach ($checklists as $idx => $chk) {
                \App\Models\MaintenanceExecutionAnswer::create([
                    'execution_id' => $exec7->id,
                    'checklist_item_id' => $chk->id,
                    'score' => $scores[$chk->sequence] ?? 4,
                    'remarks' => ($scores[$chk->sequence] ?? 4) === 1 ? 'Membutuhkan perbaikan segera' : null,
                ]);
            }
        }
    }
}
