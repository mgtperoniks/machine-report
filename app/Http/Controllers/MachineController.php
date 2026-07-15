<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    protected WarehouseRepository $warehouseRepo;

    public function __construct(WarehouseRepository $warehouseRepo)
    {
        $this->warehouseRepo = $warehouseRepo;
    }

    /**
     * Display a listing of the machines.
     */
    public function index(Request $request)
    {
        $query = Machine::query();

        // Search filter (Code, Name, Model, Manufacturer)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%");
            });
        }

        // Dropdown filters
        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('criticality')) {
            $query->where('criticality', $request->input('criticality'));
        }

        if ($request->filled('operational_status')) {
            $query->where('operational_status', $request->input('operational_status'));
        }

        // Sorting params
        $sortBy = $request->input('sort_by', 'code');
        $sortOrder = $request->input('sort_order', 'asc');

        // Apply non-health sorting at DB level
        if ($sortBy !== 'health') {
            // Special treatment for custom criticality order if sorting by criticality
            if ($sortBy === 'criticality') {
                $query->orderByRaw("
                    CASE criticality
                        WHEN 'mission_critical' THEN 1
                        WHEN 'high' THEN 2
                        WHEN 'medium' THEN 3
                        WHEN 'low' THEN 4
                        ELSE 5
                    END {$sortOrder}
                ");
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
            $machines = $query->get();
        } else {
            // Health is transient/calculated, so sort in-memory
            $machines = $query->get();
            if ($sortOrder === 'desc') {
                $machines = $machines->sortByDesc(fn($m) => $m->health_score)->values();
            } else {
                $machines = $machines->sortBy(fn($m) => $m->health_score)->values();
            }
        }

        // Fetch distinct filter options from DB for dynamic dropdown selections
        $departments = Machine::distinct()->pluck('department')->sort()->values();
        $categories = Machine::distinct()->pluck('category')->sort()->values();
        $criticalities = ['low', 'medium', 'high', 'mission_critical'];
        $operationalStatuses = ['running', 'idle', 'maintenance', 'breakdown', 'stopped'];

        return view('machines.index', compact(
            'machines',
            'departments',
            'categories',
            'criticalities',
            'operationalStatuses',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Display the Machine Passport (aggregated view model).
     */
    public function show(string $code)
    {
        // Retrieve the machine identity with associated structures
        $machine = Machine::with(['components', 'requiredSpareparts', 'documents', 'photos'])
            ->where('code', $code)
            ->firstOrFail();

        // Extract required sparepart codes and fetch stock status from Warehouse Repository
        $itemCodes = $machine->requiredSpareparts->pluck('warehouse_item_code')->toArray();
        $sparepartsDetails = $this->warehouseRepo->getItemsDetails($itemCodes);

        return view('machines.show', compact('machine', 'sparepartsDetails'));
    }
}
