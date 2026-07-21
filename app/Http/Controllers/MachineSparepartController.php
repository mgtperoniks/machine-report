<?php

namespace App\Http\Controllers;

use App\Integrations\WMS\Repositories\SparepartLookupRepositoryInterface;
use App\Integrations\WMS\Services\MachineSparepartService;
use App\Models\Machine;
use App\Models\MachineRequiredSparepart;
use Illuminate\Http\Request;

class MachineSparepartController extends Controller
{
    public function __construct(
        protected MachineSparepartService $sparepartService,
        protected SparepartLookupRepositoryInterface $sparepartLookupRepository
    ) {}

    /**
     * Autocomplete search for spareparts from WMS.
     */
    public function search(Request $request, string $machineCode)
    {
        Machine::where('code', $machineCode)->firstOrFail();
        
        $query = $request->input('q', '');
        $results = $this->sparepartService->searchSpareparts($query);
        
        return response()->json($results);
    }

    /**
     * Store machine required sparepart mapping.
     */
    public function store(Request $request, string $machineCode)
    {
        $machine = Machine::where('code', $machineCode)->firstOrFail();

        $validated = $request->validate([
            'warehouse_item_code' => 'required|string',
        ]);

        $itemCode = strtoupper(trim($validated['warehouse_item_code']));

        // Retrieve item from WMS
        $itemDto = $this->sparepartLookupRepository->getItemDetails($itemCode);
        if (!$itemDto->isOffline && str_starts_with($itemDto->name, 'Sparepart Unmapped')) {
            return response()->json([
                'message' => 'Sparepart tidak ditemukan pada Warehouse Management System.'
            ], 422);
        }

        // Validate uniqueness: duplicate prevention
        $exists = MachineRequiredSparepart::where('machine_id', $machine->id)
            ->where('warehouse_item_code', $itemCode)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Sparepart ini sudah terhubung dengan mesin.'
            ], 422);
        }

        // Store relationship only (machine_id and warehouse_item_code)
        $mapping = MachineRequiredSparepart::create([
            'machine_id' => $machine->id,
            'warehouse_item_code' => $itemCode,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mapping sparepart berhasil ditambahkan.',
            'mapping' => array_merge($itemDto->toArray(), ['mapping_id' => $mapping->id])
        ]);
    }

    /**
     * Remove machine required sparepart mapping relationship.
     */
    public function destroy(string $machineCode, MachineRequiredSparepart $mapping)
    {
        $mapping->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Mapping sparepart berhasil dihapus.'
            ]);
        }

        return back()->with('success', 'Mapping sparepart berhasil dihapus.');
    }
}
