<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('skill')?->id;

        return [
            'name' => 'required|string|unique:skills,name,' . $id,
        ];
    }
}
