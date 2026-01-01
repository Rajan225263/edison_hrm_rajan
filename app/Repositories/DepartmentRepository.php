<?php
namespace App\Repositories;

use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryInterface;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function getAll()
    {
        return Department::orderBy('name')->get();
    }

    public function create(array $data): Department
    {
        return Department::create($data);
    }
}
