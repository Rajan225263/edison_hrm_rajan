<?php

namespace App\Repositories\Contracts;

use App\Models\Sale;
use App\Enums\PerPage;

interface SaleRepositoryInterface
{
    public function getAll(PerPage $perPage = PerPage::TEN);
    public function findById($id): Sale;
    public function create(array $data): Sale;
    public function update(Sale $sale, array $data): Sale;
    public function delete(Sale $sale): bool;
    public function queryWithRelations(array $relations = []);
    public function findTrashed(int $id): Sale;
    public function restore(Sale $sale): bool;
    public function getTrashed(int $perPage);
}
