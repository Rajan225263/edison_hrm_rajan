<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        try {
            $customers = $this->users->getAll();
            return view('admin.customers.index', compact('customers'));
        } catch (Exception $e) {
            Log::error('Customer index error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while fetching customers.');
        }
    }

    public function create(User $customer)
    {
        return view('admin.customers.form', compact('customer'));
    }

    public function store(CustomerRequest $request)
    {
        try {
            $data = $request->validated();
            $this->users->create($data);

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer created successfully.');
        } catch (Exception $e) {
            Log::error('Customer store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create customer.');
        }
    }

    public function edit(User $customer)
    {
        return view('admin.customers.form', compact('customer'));
    }

    public function update(CustomerRequest $request, User $customer)
    {
        try {
            $data = $request->validated();
            $this->users->update($customer, $data);

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer updated successfully.');
        } catch (Exception $e) {
            Log::error('Customer update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update customer.');
        }
    }

    public function destroy(User $customer)
    {
        try {
            $this->users->delete($customer);
            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer deleted successfully.');
        } catch (Exception $e) {
            Log::error('Customer delete error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete customer.');
        }
    }

    public function show($id)
    {
        try {
            $customer = $this->users->findById($id);
            return view('admin.customers.show', compact('customer'));
        } catch (Exception $e) {
            Log::error('Customer show error: ' . $e->getMessage());
            return back()->with('error', 'Customer not found.');
        }
    }
}
