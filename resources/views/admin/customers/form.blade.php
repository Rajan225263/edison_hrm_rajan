@extends('admin.layout')

@section('content')
<div class="container">
    <h2 class="mb-3">{{ $customer->exists ? 'Edit Customer' : 'Add Customer' }}</h2>

    <form action="{{ $customer->exists 
            ? route('admin.customers.update', $customer->id) 
            : route('admin.customers.store') }}" 
        method="POST">
        
        @csrf
        @if($customer->exists)
            @method('PUT')
        @endif

        {{-- Name (required) --}}
        <div class="mb-3">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Email (required) --}}
        <div class="mb-3">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Phone (optional) --}}
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Password (required for create, optional for edit) --}}
        <div class="mb-3">
            <label>Password 
                @if(!$customer->exists)
                    <span class="text-danger">*</span>
                @endif
                {{ $customer->exists ? '(leave blank to keep current)' : '' }}
            </label>
            <input type="password" name="password" class="form-control">
            @error('password') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label>Confirm Password 
                @if(!$customer->exists)
                    <span class="text-danger">*</span>
                @endif
            </label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button class="btn btn-success">{{ $customer->exists ? 'Update' : 'Save' }}</button>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
