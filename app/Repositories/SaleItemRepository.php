<?php

namespace App\Repositories;

use App\Models\SaleItem;
use App\Repositories\Contracts\SaleItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SaleItemRepository implements SaleItemRepositoryInterface
{
    public function create(array $data): SaleItem
    {
        return SaleItem::create($data);
    }

    public function delete(SaleItem $item): bool
    {
        return $item->delete();
    }

    public function find(int $id): SaleItem
    {
        return SaleItem::findOrFail($id);
    }

    public function restore(int $id): SaleItem
    {
        $item = SaleItem::withTrashed()->findOrFail($id);
        $item->restore();
        return $item;
    }

    public function forceDelete(int $id): bool
    {
        $item = SaleItem::withTrashed()->findOrFail($id);
        return $item->forceDelete();
    }

    public function getAll(): Collection
    {
        return SaleItem::all();
    }

    /**
     * Get SaleItem with trashed included
     */
    public function findWithTrashed(int $id): SaleItem
    {
        return SaleItem::withTrashed()->findOrFail($id);
    }
}
