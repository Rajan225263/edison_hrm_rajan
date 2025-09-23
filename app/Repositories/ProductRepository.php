<?php

namespace App\Repositories;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\PerPage;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(PerPage $perPage = PerPage::TEN)
    {
        return Product::latest()->paginate($perPage->value);
    }

    public function findById($id)
    {
        return Product::with('notes.user')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }

    public function getAllOrderedByName(): Collection
    {
        return Product::orderBy('name')->get();
    }
}
