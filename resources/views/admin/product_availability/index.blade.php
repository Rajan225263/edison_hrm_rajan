@extends('admin.layout')

@section('content')
<div class="container">
    <h2>Product Stock List</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($availabilities as $availability)
                <tr>
                    <td>{{ $availability->product->name }}</td>
                    <td>{{ $availability->stock }}</td>
                    <td>
                        <!-- Add Stock -->
                        <form action="{{ route('admin.product_availability.addStock', $availability->product_id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="number" name="quantity" min="1" required>
                            <button type="submit" class="btn btn-success btn-sm">+ Add</button>
                        </form>

                        <!-- Subtract Stock -->
                        <form action="{{ route('admin.product_availability.subtractStock', $availability->product_id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="number" name="quantity" min="1" required>
                            <button type="submit" class="btn btn-danger btn-sm">- Subtract</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
