<?php

namespace Framework\Validation\Rules;

interface Rule
{
    public function passes(string $attribute, mixed $value);

    public function getError(): string;
}