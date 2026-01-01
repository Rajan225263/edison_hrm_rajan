@extends('layout')

@section('title', $skill->exists ? 'Edit Skill' : 'Create Skill')

@section('content')
<div class="container-fluid">

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                {{ $skill->exists ? 'Edit Skill' : 'Create Skill' }}
            </h3>
        </div>

        <form method="POST"
              action="{{ $skill->exists
                    ? route('skills.update',$skill)
                    : route('skills.store') }}">
            @csrf
            @if($skill->exists) @method('PUT') @endif

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        Please fix the errors below.
                    </div>
                @endif

                <div class="form-group">
                    <label>
                        Skill Name
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name',$skill->name) }}"
                           placeholder="Enter skill name">

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
                    {{ $skill->exists ? 'Update' : 'Save' }}
                </button>

                <a href="{{ route('skills.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
