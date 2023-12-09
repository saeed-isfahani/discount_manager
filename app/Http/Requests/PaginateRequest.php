<?php

namespace App\Http\Requests;

use App\Enums\Shop\ShopStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PaginateRequest extends FormRequest
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
            'per_page' => ['nullable', 'integer', 'min:'],
            'q' => ['nullable', 'string', 'max:30'],
            'date' => ['nullable', 'date'],
            'status' => ['nullable', new Enum(ShopStatusEnum::class)],
        ];
    }
}
