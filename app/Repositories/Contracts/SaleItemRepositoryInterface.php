<?php

namespace App\Repositories\Contracts;

use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Collection;

interface SaleItemRepositoryInterface
{

    public function create(array $data): SaleItem;
    public function delete(SaleItem $item): bool;
    public function find(int $id): SaleItem;
    public function restore(int $id): SaleItem;
    public function forceDelete(int $id): bool;
    public function getAll(): Collection;
    public function findWithTrashed(int $id): SaleItem;
}
