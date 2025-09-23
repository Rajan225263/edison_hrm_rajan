<?php

namespace App\Repositories\Contracts;

use App\Models\ProductAvailability;
use Illuminate\Database\Eloquent\Collection;

interface ProductAvailabilityRepositoryInterface
{
    public function getAll(): Collection;

    public function findByProductId(int $productId): ?ProductAvailability;

    public function addStock(int $productId, int $quantity): ProductAvailability;

    public function subtractStock(int $productId, int $quantity): ProductAvailability;
    
    public function softDelete(int $id): bool;

    public function restore(int $id): ?ProductAvailability;

    public function forceDelete(int $id): bool;
}
