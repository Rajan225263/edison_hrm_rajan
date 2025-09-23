@extends('admin.layout')

@section('content')
<div class="container">
    <h2>{{ $product->exists ? 'Edit Product' : 'Add New Product' }}</h2>

    <form action="{{ $product->exists 
            ? route('admin.products.update', $product->id) 
            : route('admin.products.store') }}"
        method="POST">
        @csrf
        @if($product->exists)
        @method('PUT')
        @endif

        {{-- Product Name --}}
        <div class="mb-3">
            <label>Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                value="{{ old('name', $product->name) }}">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <label>Price <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="price" class="form-control"
                value="{{ old('price', $product->price) }}">
            @error('price') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        @if(!$product->exists)
        <div class="mb-3">
            <label>Stock <span class="text-danger">*</span></label>
            <input type="number" min="0" name="stock" class="form-control"
                value="{{ old('stock', $product->stock) }}">
            @error('stock') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        @endif

        {{-- Description --}}
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        {{-- Notes --}}
        <div class="mb-3">
            <label>Notes</label>
            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
            <small class="text-muted">Optional</small>
        </div>

        <button class="btn btn-success">
            {{ $product->exists ? 'Update' : 'Save' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection