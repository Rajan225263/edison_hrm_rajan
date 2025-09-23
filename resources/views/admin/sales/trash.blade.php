@extends('admin.layout')
@section('content')
<div class="container">
    <h3>Trash - Deleted Sales</h3>
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
                    <form action="{{ route('admin.sales.restore', $sale->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="return confirm('Restore this sale?')">Restore</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Trash is empty.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-end">{{ $sales->links() }}</div>
</div>
@endsection