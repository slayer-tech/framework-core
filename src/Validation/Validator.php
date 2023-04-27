<?php

namespace Framework\Validation;

use Framework\Validation\Rules\Required;
use Framework\Validation\Rules\Boolean;
use Framework\Validation\Rules\Email;
use Framework\Validation\Rules\Numeric;

class Validator
{
    private $rules = [
        'required' => Required::class,
        'boolean' => Boolean::class,
        'numeric' => Numeric::class,
        'email' => Email::class,
    ];

    public function validate(array $data): array
    {
        $errors = [];

        foreach ($data as $key => $value) {
            $rules = explode('|', $value);

            foreach ($rules as $rule) {
                if (isset($this->rules[$rule])) {
                    $class = new $this->rules[$rule];

                    $validate = $class->passes($key, request()->get($key));

                    if (!$validate) {
                        $errors[$key] = str_replace(":attribute", $key, $class->getError());
                    }
                }
            }
        }

        return $errors;
    }
}