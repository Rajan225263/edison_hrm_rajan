@extends('layout')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="mb-0">Employee Details</h4>
        </div>
    </div>

    {{-- Centered Content (Same as Form Pages) --}}
    <div class="row justify-content-left">
        <div class="col-md-12 col-lg-12">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i>
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </h5>
                </div>

                <div class="card-body px-4 py-3">

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Email</div>
                        <div class="col-md-8">{{ $employee->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Department</div>
                        <div class="col-md-8">
                            <span class="badge badge-info">
                                {{ $employee->department->name }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Skills</div>
                        <div class="col-md-8">
                            @forelse($employee->skills as $skill)
                            <span class="badge badge-secondary mr-1 mb-1">
                                {{ $skill->name }}
                            </span>
                            @empty
                            <span class="text-muted">No skills assigned</span>
                            @endforelse
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4 font-weight-bold">Created At</div>
                        <div class="col-md-8">
                            {{ $employee->created_at->format('d M Y') }}
                        </div>
                    </div>

                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>

                    <a href="{{ route('employees.edit',$employee) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection