<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('customer')?->id;

        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => $id
                ? 'nullable|string|confirmed|min:6'
                : 'required|string|confirmed|min:6',
        ];
    }

    /**
     * Overriding validated() to handle passwords
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated();

        // If a password is given, it will hash it.
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']); // Will exclude null password in update case
        }

        return $data;
    }
}
