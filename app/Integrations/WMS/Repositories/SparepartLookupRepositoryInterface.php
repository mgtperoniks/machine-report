<?php

namespace App\Integrations\WMS\Repositories;

use App\Integrations\WMS\DTOs\SparepartItemDTO;

interface SparepartLookupRepositoryInterface
{
    /**
     * Get details for a single ERP code from WMS.
     */
    public function getItemDetails(string $erpCode): SparepartItemDTO;

    /**
     * Get details for multiple ERP codes in a single batch query.
     * Returns associative array keyed by ERP Code.
     *
     * @param array<string> $erpCodes
     * @return array<string, SparepartItemDTO>
     */
    public function getItemsDetails(array $erpCodes): array;

    /**
     * Search spareparts in WMS with priority: ERP Code -> Barcode -> Name -> Brand.
     *
     * @return array<SparepartItemDTO>
     */
    public function searchItems(string $query, int $limit = 10): array;
}
