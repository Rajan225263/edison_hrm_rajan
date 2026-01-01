<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Authorize request
     */
    public function authorize(): bool
    {
        // Auth middleware already applied
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        // Employee ID for update (null on create)
        $employeeId = $this->route('employee')?->id;

        return [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:150|unique:employees,email,' . $employeeId,
            'department_id' => 'required|exists:departments,id',
            'skills'     => 'nullable|array',
            'skills.*'   => 'exists:skills,id',
        ];
    }

    /**
     * Custom validation messages (optional but professional)
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'email.required'      => 'Email address is required.',
            'email.email'         => 'Please provide a valid email address.',
            'email.unique'        => 'This email address is already in use.',
            'department_id.required' => 'Please select a department.',
            'department_id.exists'   => 'Selected department is invalid.',
            'skills.array'         => 'Skills must be an array.',
            'skills.*.exists'      => 'One or more selected skills are invalid.',
        ];
    }
}
