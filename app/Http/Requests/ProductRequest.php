<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product')?->id; // If updated, product id

        return [
            'name'        => 'required|string|max:255|unique:products,name,' . $id,
            'price'       => 'required|numeric',
            'stock'       => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'note'        => 'nullable|string|max:1000',
        ];
    }
}
