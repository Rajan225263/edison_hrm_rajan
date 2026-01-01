@extends('layout')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Employee List</h3>

            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Employee
            </a>
        </div>
    </div>

    {{-- Success / Error Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Department Filter --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <select id="departmentFilter" class="form-control">
                <option value="">-- All Departments --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Employee Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Skills</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody id="employeeTable">
                    @include('employees.partials.table', ['employees' => $employees])
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $employees->links() }}
    </div>

</div>
@endsection

@push('scripts')
<script>
    $('#departmentFilter').on('change', function () {
        $.ajax({
            url: "{{ route('employees.filter') }}",
            data: {
                department_id: $(this).val()
            },
            success: function (html) {
                $('#employeeTable').html(html);
            },
            error: function () {
                alert('Failed to load employees');
            }
        });
    });
</script>
@endpush
