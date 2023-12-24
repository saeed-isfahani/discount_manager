<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name' => 'unique:Spatie\Permission\Models\Role|string|min:5,max:50',
            "permissions"    => [
                'required',
                'array', // input must be an array
                'min:1'  // there must be three members in the array
            ],
            "permissions.*"  => [
                'required',
                'string',   // input must be of type string
                'distinct', // members of the array must be unique
                'exists:permissions,name'     // each string must exist in specific table field
            ]
        ];
    }
}
