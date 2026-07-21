<?php

namespace App\Integrations\WMS\Services;

use App\Integrations\WMS\DTOs\SparepartItemDTO;
use App\Integrations\WMS\Repositories\SparepartLookupRepositoryInterface;
use App\Models\Machine;
use App\Models\MachineRequiredSparepart;

class MachineSparepartService
{
    public function __construct(
        protected SparepartLookupRepositoryInterface $sparepartLookupRepository
    ) {}

    /**
     * Get complete live spareparts data for a machine within strict 3-query budget.
     *
     * Query 1: MRM Machine required spareparts
     * Query 2: Batch lookup to WMS DB
     * Query 3: Batch lookup for shared machine usage in MRM DB
     */
    public function getMachineSparepartsView(Machine $machine): array
    {
        // Query 1 (MRM DB): Fetch mapping records for this machine
        $requiredItems = $machine->requiredSpareparts;
        if ($requiredItems->isEmpty()) {
            return [];
        }

        $erpCodes = $requiredItems->pluck('warehouse_item_code')->unique()->toArray();

        // Query 2 (WMS DB): Single batch lookup to WMS database
        $wmsDetailsMap = $this->sparepartLookupRepository->getItemsDetails($erpCodes);

        // Query 3 (MRM DB): Single batch query for shared machine usage across system
        $sharedUsageRecords = MachineRequiredSparepart::whereIn('warehouse_item_code', $erpCodes)
            ->with('machine:id,code,name')
            ->get()
            ->groupBy('warehouse_item_code');

        $wmsBaseUrl = rtrim(config('integration.wms.base_url', 'http://127.0.0.1:8000/items/'), '/') . '/';
        $warningMax = config('integration.wms.stock_thresholds.warning_max', 5);

        $result = [];

        foreach ($requiredItems as $required) {
            $code = $required->warehouse_item_code;
            /** @var SparepartItemDTO $dto */
            $dto = $wmsDetailsMap[$code] ?? SparepartItemDTO::offlineFallback($code, isOffline: true, mappingId: $required->id);

            // Re-assign local mapping ID
            $dtoWithMapping = new SparepartItemDTO(
                erpCode: $dto->erpCode,
                variantId: $dto->variantId,
                name: $dto->name,
                brand: $dto->brand,
                unit: $dto->unit,
                barcode: $dto->barcode,
                location: $dto->location,
                supplier: $dto->supplier,
                stock: $dto->stock,
                isAvailable: $dto->isAvailable,
                isOffline: $dto->isOffline,
                mappingId: $required->id
            );

            // Resolve stock status badge & color
            $status = $this->resolveStockStatus($dtoWithMapping, $warningMax);

            // Resolve shared machines
            $sharedGroup = $sharedUsageRecords->get($code, collect());
            $sharedMachines = $sharedGroup->map(function ($item) {
                return [
                    'code' => $item->machine?->code ?? '-',
                    'name' => $item->machine?->name ?? 'Unknown Machine',
                ];
            })->unique('code')->values()->toArray();

            $openWmsUrl = $dto->variantId ? ($wmsBaseUrl . $dto->variantId) : null;

            $result[] = [
                'mapping_id' => $required->id,
                'dto' => $dtoWithMapping,
                'status' => $status,
                'open_wms_url' => $openWmsUrl,
                'shared_machines' => $sharedMachines,
                'shared_count' => count($sharedMachines),
            ];
        }

        return $result;
    }

    /**
     * Resolve stock status indicator, badges, and colors.
     */
    protected function resolveStockStatus(SparepartItemDTO $dto, int $warningMax): array
    {
        if ($dto->isOffline) {
            return [
                'code' => 'offline',
                'label' => 'WMS Offline',
                'badge_class' => 'bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600',
                'icon' => '⚪',
            ];
        }

        if ($dto->stock === 0) {
            return [
                'code' => 'danger',
                'label' => 'Out of Stock',
                'badge_class' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/40 dark:text-red-400 dark:border-red-800',
                'icon' => '🔴',
            ];
        }

        if ($dto->stock <= $warningMax) {
            return [
                'code' => 'warning',
                'label' => 'Low Stock',
                'badge_class' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/40 dark:text-amber-400 dark:border-amber-800',
                'icon' => '🟡',
            ];
        }

        return [
            'code' => 'success',
            'label' => 'Available',
            'badge_class' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-400 dark:border-emerald-800',
            'icon' => '🟢',
        ];
    }

    /**
     * Autocomplete search for spareparts.
     */
    public function searchSpareparts(string $query, int $limit = 10): array
    {
        $dtos = $this->sparepartLookupRepository->searchItems($query, $limit);

        return array_map(function (SparepartItemDTO $dto) {
            return [
                'code' => $dto->erpCode,
                'erp_code' => $dto->erpCode,
                'name' => $dto->name,
                'brand' => $dto->brand,
                'barcode' => $dto->barcode,
                'location' => $dto->location,
                'stock' => $dto->stock,
                'unit' => $dto->unit,
                'display_label' => "{$dto->erpCode} - {$dto->name} (" . ($dto->brand !== '-' ? $dto->brand : 'No Brand') . ")",
            ];
        }, $dtos);
    }
}
