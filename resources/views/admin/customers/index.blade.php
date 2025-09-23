@extends('admin.layout')

@section('content')
<div class="container">
    <h2>Customer List</h2>
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary mb-3">+ Add Customer</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>
                    <a href="{{ route('admin.customers.show',$customer->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('admin.customers.edit',$customer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.customers.destroy',$customer->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this customer?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">No customers found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $customers->links() }}
</div>
@endsection
