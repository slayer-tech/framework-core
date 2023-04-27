<?php

namespace Framework\Validation\Rules;

class Numeric implements Rule
{
    public function passes(string $attribute, mixed $value): bool
    {
        if ($value == null) {
            return true;
        }

        return is_numeric($value);
    }

    public function getError(): string
    {
        return "The :attribute field must be numeric";
    }
}