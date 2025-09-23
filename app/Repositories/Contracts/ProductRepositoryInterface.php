<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\PerPage;

interface ProductRepositoryInterface
{
    public function getAll(PerPage $perPage = PerPage::TEN);
    public function findById($id);
    public function create(array $data);
    public function update(Product $product, array $data);
    public function delete(Product $product);
    public function getAllOrderedByName(): Collection;
}
