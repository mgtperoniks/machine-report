<?php

namespace App\Repositories;

use App\Integrations\WMS\Repositories\SparepartLookupRepositoryInterface;

class WarehouseRepositoryAdapter implements WarehouseRepositoryInterface
{
    public function __construct(
        protected SparepartLookupRepositoryInterface $lookupRepository
    ) {}

    public function getItemDetails(string $itemCode): array
    {
        return $this->lookupRepository->getItemDetails($itemCode)->toArray();
    }

    public function getItemsDetails(array $itemCodes): array
    {
        $dtos = $this->lookupRepository->getItemsDetails($itemCodes);
        $result = [];
        foreach ($dtos as $code => $dto) {
            $result[$code] = $dto->toArray();
        }
        return $result;
    }

    public function searchItems(string $query): array
    {
        $dtos = $this->lookupRepository->searchItems($query);
        return array_map(fn($dto) => $dto->toArray(), $dtos);
    }
}
