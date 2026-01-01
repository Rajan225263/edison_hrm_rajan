<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Http\Requests\DepartmentRequest;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Log;


class DepartmentController extends Controller
{
    protected $departments;

    public function __construct(DepartmentRepositoryInterface $departments)
    {
        $this->departments = $departments;
    }

    public function index()
    {
        try {
            $departments = $this->departments->getAll();
            return view('departments.index', compact('departments'));
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with('error', 'Failed to load departments');
        }
    }

    public function create()
    {
        return view('departments.form', [
            'department' => new Department()
        ]);
    }

    public function store(DepartmentRequest $request)
    {
        try {
            $this->departments->create($request->only('name'));

            return redirect()
                ->route('departments.index')
                ->with('success', 'Department created successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with('error', 'Something went wrong');
        }
    }
}
