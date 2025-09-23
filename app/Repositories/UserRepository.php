<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\PerPage;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(PerPage $perPage = PerPage::TEN)
    {
        return User::latest()->paginate($perPage->value);
    }

    public function findById($id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function getAllOrderedByName(): Collection
    {
        return User::orderBy('name')->get();
    }
}
