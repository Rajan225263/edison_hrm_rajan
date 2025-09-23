@extends('admin.layout')

@section('content')
<div class="container">
    <h2>Customer Details</h2>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $customer->id }}</td></tr>
        <tr><th>Name</th><td>{{ $customer->name }}</td></tr>
        <tr><th>Email</th><td>{{ $customer->email }}</td></tr>
        <tr><th>Phone</th><td>{{ $customer->phone }}</td></tr>
        <tr><th>Created At</th><td>{{ $customer->created_at }}</td></tr>
    </table>

    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
