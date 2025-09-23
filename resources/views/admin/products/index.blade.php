@extends('admin.layout')

@section('content')
<div class="container">
    <h2 class="mb-3">Product List</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">+ Add Product</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Initial Stock</th>
                <th>Created</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price,2) }}</td>
                <td>{{ number_format($product->stock) }}</td>
                <td>{{ $product->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.products.show',$product->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('admin.products.edit',$product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.products.destroy',$product->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->links() }}
</div>
@endsection