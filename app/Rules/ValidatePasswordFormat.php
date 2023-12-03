<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidatePasswordFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\.~!@#$%^&*_+=-]).{8,}$/', $value)) {
            $fail(__('auth.messages.the_password_must_contain_uppercase_and_lowercase_letters_and_special_characters_and_have_at_least_8_characters'));
        }
    }
}
