<?php

namespace Framework\Validation\Rules;

class Boolean implements Rule
{
    public function passes(string $attribute, mixed $value): bool
    {
        if ($value == '1' || $value = '0' || $value = 'true' || $value == 'false') {
            return true;
        }

        return ($value == '1' || $value = '0' || $value = 'true' || $value == 'false');
    }

    public function getError(): string
    {
        return "The :attribute field must be boolean";
    }
}