<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'description' => 'required|string|max:500',
            'image' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'expire_at' => 'nullable|date',
            'expire_soon' => 'nullable|boolean',
            'shop_id' => 'required|exists:shops,id',
            "product_discounts"    => [
                'required',
                'array', // input must be an array
                'min:1'  // there must be three members in the array
            ],
            "product_discounts.*"    => [
                'required',
                'array', // input must be an array
                'size:4'  // there must be three members in the array
            ],
            "product_discounts.*.good_count_from"  => 'required|numeric',
            "product_discounts.*.good_count_to"  => 'required|numeric',
            "product_discounts.*.percent"  => 'required|numeric',
            "product_discounts.*.price"  => 'required|numeric',
        ];
    }
}
