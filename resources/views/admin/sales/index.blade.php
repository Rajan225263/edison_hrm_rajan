@extends('admin.layout')
@section('content')
<div class="container">
    <h3>Sales List</h3>
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3"><input type="text" name="customer" class="form-control" placeholder="Customer Name" value="{{ request('customer') }}"></div>
            <div class="col-md-3"><input type="text" name="product" class="form-control" placeholder="Product Name" value="{{ request('product') }}"></div>
            <div class="col-md-3"><input type="date" name="from" class="form-control" value="{{ request('from') }}"></div>
            <div class="col-md-3"><input type="date" name="to" class="form-control" value="{{ request('to') }}"></div>
        </div>
        <div class="mt-2">
            <button class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>{{ $sale->sale_date }}</td>
                <td>{{ number_format($sale->grand_total, 2) }}</td>
                <td>
                    <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn btn-info btn-sm">View</a>
                    <form action="{{ route('admin.sales.destroy', $sale->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No sales found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-between">
        <div>Total this page: <strong>{{ number_format($pageTotal,2) }} BDT</strong></div>
        <div>{{ $sales->links() }}</div>
    </div>
</div>
@endsection