<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class assignPermissionRequest extends FormRequest
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
            "permissions"    => [
                'required',
                'array', // input must be an array
                'min:1'  // there must be three members in the array
            ],
            "permissions.*"  => [
                'required',
                'string',   // input must be of type string
                'distinct', // members of the array must be unique
                'min:3'     // each string must have min 3 chars
            ]
        ];
    }
}
