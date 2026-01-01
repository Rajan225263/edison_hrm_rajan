@extends('layout')

@section('title', $employee->exists ? 'Edit Employee' : 'Create Employee')

@section('content')
<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                {{ $employee->exists ? 'Edit Employee' : 'Create Employee' }}
            </h3>
        </div>

        <form method="POST"
            action="{{ $employee->exists ? route('employees.update',$employee) : route('employees.store') }}">
            @csrf
            @if($employee->exists) @method('PUT') @endif

            <div class="card-body">

                {{-- Global validation error --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the errors below.
                </div>
                @endif

                {{-- First Name --}}
                <div class="form-group">
                    <label>First Name <span class="text-danger">*</span></label>
                    <input type="text"
                        name="first_name"
                        class="form-control @error('first_name') is-invalid @enderror"
                        value="{{ old('first_name',$employee->first_name) }}">

                    @error('first_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Last Name --}}
                <div class="form-group">
                    <label>Last Name <span class="text-danger">*</span></label>
                    <input type="text"
                        name="last_name"
                        class="form-control @error('last_name') is-invalid @enderror"
                        value="{{ old('last_name',$employee->last_name) }}">

                    @error('last_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email <span class="text-danger">*</span></label>

                    <input type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email',$employee->email) }}">

                    <small id="email-feedback"></small>

                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>


                {{-- Department --}}
                <div class="form-group">
                    <label>Department <span class="text-danger">*</span></label>
                    <select name="department_id"
                        class="form-control @error('department_id') is-invalid @enderror">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dep)
                        <option value="{{ $dep->id }}"
                            {{ old('department_id',$employee->department_id)==$dep->id?'selected':'' }}>
                            {{ $dep->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('department_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Skills --}}
                <div class="form-group">
                    <label>Skills</label>
                    <select name="skills[]"
                        id="skills"
                        class="form-control"
                        multiple>
                        @foreach($skills as $skill)
                        <option value="{{ $skill->id }}"
                            {{ collect(old('skills',$employee->skills->pluck('id')->toArray()))->contains($skill->id) ? 'selected' : '' }}>
                            {{ $skill->name }}
                        </option>
                        @endforeach
                    </select>

                    <small class="text-muted">
                        You can select multiple skills
                    </small>
                </div>

            </div>

            <div class="card-footer">
                <button class="btn btn-success">
                    <i class="fas fa-save"></i>
                    {{ $employee->exists ? 'Update' : 'Save' }}
                </button>

                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        $('#skills').on('change', function() {
            console.log('Selected skills:', $(this).val());
        });
    });

    $(document).ready(function() {

        let typingTimer;
        const delay = 500;

        $('#email').on('keyup', function() {
            clearTimeout(typingTimer);

            let email = $(this).val();
            let employeeId = "{{ $employee->id ?? '' }}";

            if (email.length < 5) {
                $('#email-feedback').text('').removeClass('text-danger text-success');
                return;
            }

            typingTimer = setTimeout(function() {
                $.ajax({
                    url: "{{ route('employees.checkEmail') }}",
                    type: "GET",
                    data: {
                        email: email,
                        employee_id: employeeId
                    },
                    success: function(response) {
                        if (response.exists) {
                            $('#email-feedback')
                                .text('❌ This email is already taken')
                                .removeClass('text-success')
                                .addClass('text-danger');
                        } else {
                            $('#email-feedback')
                                .text('✅ Email is available')
                                .removeClass('text-danger')
                                .addClass('text-success');
                        }
                    }
                });
            }, delay);
        });

    });
</script>
@endpush
