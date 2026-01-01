@extends('layout')

@section('content')
<div class="container-fluid px-4"> {{-- 👈 left-right spacing control --}}

    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Skills</h4>
            <a href="{{ route('skills.create') }}" class="btn btn-primary">
                Add Skill
            </a>
        </div>
    </div>

    {{-- Success / Error --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Table --}}
    <div class="row">
        <div class="col-12"> {{-- 👈 same width as department & employee index --}}

            <div class="card shadow-sm">
                <div class="card-body p-0">

                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60">#</th>
                                <th>Skill Name</th>
                                <th width="180">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($skills as $skill)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $skill->name }}</td>
                                <td>{{ $skill->created_at->format('d M Y') }}</td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No skills found
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