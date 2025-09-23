<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\PerPage;

interface UserRepositoryInterface
{
    public function getAll(PerPage $perPage = PerPage::TEN);
    public function findById($id): ?User;
    public function create(array $data): User;
    public function update(User $user, array $data): bool;
    public function delete(User $user): bool;
    public function getAllOrderedByName(): Collection;
}
