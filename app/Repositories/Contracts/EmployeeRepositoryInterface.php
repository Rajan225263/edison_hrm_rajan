<?php

namespace App\Repositories\Contracts;

use App\Models\Employee;

interface EmployeeRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update(Employee $employee, array $data);
    public function delete(Employee $employee);
    public function filterByDepartment($departmentId);

}
