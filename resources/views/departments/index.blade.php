@extends('layout')

@section('content')
<div class="container-fluid px-4"> {{-- 👈 left-right spacing control --}}

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Departments</h4>
            <a href="{{ route('departments.create') }}" class="btn btn-primary">
                Add Department
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12"> {{-- 👈 same width as employee index --}}

            <div class="card shadow-sm">
                <div class="card-body p-0">

                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60">#</th>
                                <th>Department Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $dep)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dep->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">
                                        No departments found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
