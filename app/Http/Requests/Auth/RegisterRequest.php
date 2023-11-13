<?php

namespace App\Http\Requests\Auth;

use App\Rules\ValidateMobileFormat;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            "mobile" => ['required', 'numeric', new ValidateMobileFormat],
            "code" => ['required', 'numeric', 'digits:6'],
            "first_name" => ['required', 'string', 'min:3', 'max:20'],
            "last_name" => ['required', 'string', 'min:3', 'max:20'],
            "email" => ['email'],
            "mobile" => ['required', 'numeric', new ValidateMobileFormat]
        ];
    }
}
