<?php

namespace App\Repositories\Contracts;

use App\Models\Department;

interface DepartmentRepositoryInterface
{
    public function getAll();
    public function create(array $data): Department;
}
