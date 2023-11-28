<?php

namespace App\Http\Requests;

use App\Rules\ValidateMobileFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShopRequest extends FormRequest
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
            'title' => ['required'],
            'category_id' => ['required'],
            'mobile' => ['required', 'numeric', new ValidateMobileFormat],
            'licence_number' => ['required', 'alpha_num:ascii',],
            'shop_number' => ['required'],
            'province_id' => ['required', Rule::exists('province_cities', 'id')->where('type', 'province')],
            'city_id' => ['required', Rule::exists('province_cities', 'id')->where('type', 'city')],
            'location' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'address' => ['required'],
        ];
    }
}
