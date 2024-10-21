<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UpperCase implements Rule
{
    public function passes($attribute, $value)
    {
        // 檢查每個單詞的首字母是否為大寫
        return preg_match('/^([A-Z][a-z]*)(\s[A-Z][a-z]*)*$/', $value);
    }

    public function message()
    {
        return 'Name is not capitalized.';
    }
}
