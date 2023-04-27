<?php

namespace Framework\Validation\Rules;

class Required implements Rule
{
    public function passes(string $attribute, mixed $value): bool
    {
        return isset($value) && strlen($value) > 0;
    }

    public function getError(): string
    {
        return "The :attribute field required";
    }
}