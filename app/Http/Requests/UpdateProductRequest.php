<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:5,max:100',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric',
            'min_price' => 'numeric',
            'max_price' => 'numeric',
            'category_id' => 'required|exists:categories,id',
            'expire_at' => 'nullable|date',
            'expire_soon' => 'nullable|boolean'
        ];
    }
}
