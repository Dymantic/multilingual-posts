<?php

namespace Dymantic\MultilingualPosts\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiresLanguage implements Rule
{
    public function passes($attribute, $value)
    {
        return collect($value)
                ->filter(function($field_value, $key) { return is_numeric($key); })
                ->count() === 0;
    }

    public function message()
    {
        return 'The :attribute must contain a language entry.';
    }
}