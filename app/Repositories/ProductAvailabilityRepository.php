<?php

namespace App\Repositories;

use App\Models\ProductAvailability;
use App\Repositories\Contracts\ProductAvailabilityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductAvailabilityRepository implements ProductAvailabilityRepositoryInterface
{
    public function getAll(): Collection
    {
        return ProductAvailability::with('product')->get();
    }

    public function findByProductId(int $productId): ?ProductAvailability
    {
        return ProductAvailability::where('product_id', $productId)->first();
    }

    public function addStock(int $productId, int $quantity): ProductAvailability
    {
        $availability = ProductAvailability::firstOrCreate(
            ['product_id' => $productId],
            ['stock' => 0]
        );

        $availability->increment('stock', $quantity);

        return $availability;
    }

    public function subtractStock(int $productId, int $quantity): ProductAvailability
    {
        $availability = $this->findByProductId($productId);
        if (!$availability) {
            throw new \Exception("No stock available for product ID: {$productId}");
        }

        $newStock = max(0, $availability->stock - $quantity);
        $availability->update(['stock' => $newStock]);

        return $availability;
    }

    public function softDelete(int $id): bool
    {
        $availability = ProductAvailability::findOrFail($id);
        return (bool) $availability->delete();
    }

    public function restore(int $id): ?ProductAvailability
    {
        $availability = ProductAvailability::withTrashed()->findOrFail($id);
        $availability->restore();
        return $availability;
    }

    public function forceDelete(int $id): bool
    {
        $availability = ProductAvailability::withTrashed()->findOrFail($id);
        return (bool) $availability->forceDelete();
    }
}
