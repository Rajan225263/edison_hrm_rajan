@extends('layout')

@section('title', $department->exists ? 'Edit Department' : 'Create Department')

@section('content')
<div class="container-fluid">

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                {{ $department->exists ? 'Edit Department' : 'Create Department' }}
            </h3>
        </div>

        <form method="POST"
              action="{{ $department->exists
                    ? route('departments.update', $department)
                    : route('departments.store') }}">
            @csrf
            @if($department->exists)
                @method('PUT')
            @endif

            <div class="card-body">

                {{-- Global Validation Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Please fix the errors below.
                    </div>
                @endif

                {{-- Department Name --}}
                <div class="form-group">
                    <label>
                        Department Name
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $department->name) }}"
                           placeholder="Enter department name">

                    @error('name')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            <div class="card-footer">
                <button class="btn btn-success">
                    <i class="fas fa-save"></i>
                    {{ $department->exists ? 'Update' : 'Save' }}
                </button>

                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
