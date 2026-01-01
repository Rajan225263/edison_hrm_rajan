<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Models\Department;
use App\Models\Skill;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    protected $employees;

    public function __construct(EmployeeRepositoryInterface $employees)
    {
        $this->employees = $employees;
    }

    public function index()
    {
        return view('employees.index', [
            'employees' => $this->employees->getAll(),
            'departments' => Department::all()
        ]);
    }

    public function create(Employee $employee)
    {
        return view('employees.form', [
            'employee' => new Employee(),
            'departments' => Department::orderBy('name')->get(),
            'skills' => Skill::orderBy('name')->get(),
        ]);
    }



    public function store(EmployeeRequest $request)
    {
        try {
            DB::beginTransaction();

            $employee = $this->employees->create(
                $request->only('first_name', 'last_name', 'email', 'department_id')
            );

            $employee->skills()->sync($request->skills ?? []);

            DB::commit();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Employee Store Error', [
                'message' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function show($id)
    {
        return view('employees.show', [
            'employee' => $this->employees->findById($id)
        ]);
    }

    public function edit(Employee $employee)
    {
        return view('employees.form', [
            'employee' => $employee->load('skills'),
            'departments' => Department::orderBy('name')->get(),
            'skills' => Skill::orderBy('name')->get(),
        ]);
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        try {
            DB::beginTransaction();

            $this->employees->update(
                $employee,
                $request->only('first_name', 'last_name', 'email', 'department_id')
            );

            $employee->skills()->sync($request->skills ?? []);

            DB::commit();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Employee Update Error', [
                'employee_id' => $employee->id,
                'message' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update employee.');
        }
    }


    public function destroy(Employee $employee)
    {
        try {
            $this->employees->delete($employee);

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee deleted successfully');
        } catch (\Throwable $e) {
            Log::error('Employee Delete Error', [
                'employee_id' => $employee->id,
                'message' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to delete employee.');
        }
    }
    public function filterByDepartment(Request $request)
    {
        try {
            $employees = $this->employees
                ->filterByDepartment($request->department_id);

            return view('employees.partials.table', compact('employees'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to filter employees'
            ], 500);
        }
    }

    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $employeeId = $request->employee_id; // for edit

        $exists = Employee::where('email', $email)
            ->when($employeeId, function ($q) use ($employeeId) {
                $q->where('id', '!=', $employeeId);
            })
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }
}
