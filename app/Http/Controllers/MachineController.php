<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MasterDepartment;
use App\Models\MasterMachineCategory;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        // 1. Lifecycle Status Filter (Default to ACTIVE)
        $statusFilter = strtoupper($request->input('status_filter', 'ACTIVE'));
        if ($statusFilter !== 'ALL') {
            if (in_array($statusFilter, ['ACTIVE', 'INACTIVE', 'RETIRED'])) {
                $query->where('lifecycle_status', $statusFilter);
                if ($statusFilter === 'ACTIVE') {
                    $query->where('is_active', true);
                } else {
                    $query->where('is_active', false);
                }
            }
        }

        // 2. Search filter (Code, Name, Model, Manufacturer)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%");
            });
        }

        // 3. Dropdown filters
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

        // Fetch distinct filter options from active Master Tables for dynamic dropdown selections
        $departments = MasterDepartment::where('is_active', true)->orderBy('sort_order')->pluck('name')->values();
        $categories = MasterMachineCategory::where('is_active', true)->orderBy('sort_order')->pluck('name')->values();
        $criticalities = ['low', 'medium', 'high', 'mission_critical'];
        $operationalStatuses = ['running', 'idle', 'maintenance', 'breakdown', 'stopped'];

        return view('machines.index', compact(
            'machines',
            'departments',
            'categories',
            'criticalities',
            'operationalStatuses',
            'sortBy',
            'sortOrder',
            'statusFilter'
        ));
    }

    /**
     * Show the form for creating a new machine.
     */
    public function create()
    {
        $departments = MasterDepartment::where('is_active', true)->orderBy('sort_order')->get();
        $categories = MasterMachineCategory::where('is_active', true)->orderBy('sort_order')->get();
        $lifecycles = ['ACTIVE', 'INACTIVE', 'RETIRED'];

        return view('machines.create', compact('departments', 'categories', 'lifecycles'));
    }

    /**
     * Store a newly created machine in storage.
     */
    public function store(Request $request)
    {
        // Enforce case-insensitive uniqueness and uppercase code
        if ($request->filled('code')) {
            $request->merge(['code' => strtoupper($request->input('code'))]);
        }

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:machines,code',
                'regex:/^[A-Z0-9\-]+$/' // alpha-numeric and dash only
            ],
            'name' => 'required|string|max:255',
            'department' => [
                'required',
                'string',
                Rule::exists('master_departments', 'name')->where('is_active', true)
            ],
            'category' => [
                'required',
                'string',
                Rule::exists('master_machine_categories', 'name')->where('is_active', true)
            ],
            'production_area' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'installation_date' => 'nullable|date',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'lifecycle_status' => 'required|string|in:ACTIVE,INACTIVE,RETIRED',
        ], [
            'code.regex' => 'Kode Mesin hanya boleh berisi huruf, angka, dan tanda hubung (-).',
            'code.unique' => 'Kode Mesin sudah terdaftar di sistem.',
        ]);

        // Auto-assign is_active based on lifecycle_status
        $isActive = ($validated['lifecycle_status'] === 'ACTIVE');

        Machine::create(array_merge($validated, [
            'is_active' => $isActive,
            'created_by' => auth()->id(),
            'criticality' => 'medium', // Default fallback
            'operational_status' => 'running', // Default fallback
        ]));

        return redirect()->route('machines.show', $validated['code'])
            ->with('success', "Mesin {$validated['code']} ({$validated['name']}) berhasil terdaftar.");
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

    /**
     * Show the form for editing the specified machine.
     */
    public function edit(string $code)
    {
        $machine = Machine::where('code', $code)->firstOrFail();
        $departments = MasterDepartment::where('is_active', true)->orderBy('sort_order')->get();
        $categories = MasterMachineCategory::where('is_active', true)->orderBy('sort_order')->get();
        $lifecycles = ['ACTIVE', 'INACTIVE', 'RETIRED'];

        return view('machines.edit', compact('machine', 'departments', 'categories', 'lifecycles'));
    }

    /**
     * Update the specified machine in storage.
     */
    public function update(Request $request, string $code)
    {
        $machine = Machine::where('code', $code)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => [
                'required',
                'string',
                Rule::exists('master_departments', 'name')->where('is_active', true)
            ],
            'category' => [
                'required',
                'string',
                Rule::exists('master_machine_categories', 'name')->where('is_active', true)
            ],
            'production_area' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'installation_date' => 'nullable|date',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'lifecycle_status' => 'required|string|in:ACTIVE,INACTIVE,RETIRED',
        ]);

        // Auto-assign is_active based on lifecycle_status
        $isActive = ($validated['lifecycle_status'] === 'ACTIVE');

        // Update attributes, keeping 'code' immutable
        $machine->update(array_merge($validated, [
            'is_active' => $isActive,
        ]));

        return redirect()->route('machines.show', $machine->code)
            ->with('success', "Informasi mesin {$machine->code} berhasil diperbarui.");
    }

    /**
     * Archive the specified machine in storage (No hard deletes).
     */
    public function destroy(string $code)
    {
        $machine = Machine::where('code', $code)->firstOrFail();

        // Perform archive operation instead of physical deletion
        $machine->update([
            'is_active' => false,
            'lifecycle_status' => 'INACTIVE'
        ]);

        return redirect()->route('machines.index')
            ->with('success', "Mesin {$machine->code} telah diarsipkan dan statusnya diubah menjadi Nonaktif.");
    }
}
