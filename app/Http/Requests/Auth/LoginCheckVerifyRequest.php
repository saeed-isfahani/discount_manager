<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginCheckVerifyRequest extends FormRequest
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
            'mobile' => 'required|regex:/(98)[0-9]{10}$/',
            'code' => 'required|integer|min:10000|max:99999',
        ];
    }

    public function messages()
    {
        return [
            'mobile.regex' => __('auth.messages.Entered mobile number must be 10 digits and start with 98'),
        ];
    }
}
