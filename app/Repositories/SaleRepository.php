<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Repositories\Contracts\SaleRepositoryInterface;
use App\Enums\PerPage;

class SaleRepository implements SaleRepositoryInterface
{
    public function getAll(PerPage $perPage = PerPage::TEN)
    {
        return Sale::with(['user', 'items.product'])->latest()->paginate($perPage->value);
    }

    public function findById($id): Sale
    {
        return Sale::with(['user', 'items.product'])->findOrFail($id);
    }

    public function create(array $data): Sale
    {
        return Sale::create($data);
    }

    public function update(Sale $sale, array $data): Sale
    {
        $sale->update($data);
        return $sale;
    }

    public function delete(Sale $sale): bool
    {
        return $sale->delete();
    }

    public function queryWithRelations(array $relations = [])
    {
        return Sale::with($relations);
    }

    /**
     * Finding trashed (soft deleted) sales
     */
    public function findTrashed(int $id): Sale
    {
        return Sale::onlyTrashed()->findOrFail($id);
    }

    /**
     * Paginated trashed sales
     */
    public function getTrashed(int $perPage)
    {
        return Sale::onlyTrashed()->with('user')->latest()->paginate($perPage);
    }


    /**
     * trashed Sale Restore
     */
    public function restore(Sale $sale): bool
    {
        return (bool) $sale->restore();
    }
}
