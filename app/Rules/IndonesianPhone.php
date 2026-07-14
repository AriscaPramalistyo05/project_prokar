<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IndonesianPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^(08|628|\+628)[0-9]{7,12}$/', $value)) {
            $fail('Format :attribute tidak valid. Harus diawali 08, 628, atau +628 dan panjang 10-15 angka.');
        }
    }
}
