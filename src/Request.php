<?php

namespace Framework;

use Framework\Validation\Validator;

class Request
{
    private $errors = [];

    public function __construct()
    {
        $this->bootstrapSelf();
    }

    private function bootstrapSelf(): void
    {
        foreach ($_SERVER as $key => $value) {
            $result = strtolower($key);

            preg_match_all('/_[a-z]/', $result, $matches);

            foreach ($matches[0] as $match) {
                $c      = str_replace('_', '', strtoupper($match));
                $result = str_replace($match, $c, $result);
            }

            $this->$result = $value;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): Request
    {
        $this->errors = $errors;

        return $this;
    }

    public function getBody()
    {
        $body = [];
        if ($this->requestMethod == 'GET') {
            parse_str($this->queryString, $body);

            return $body;
        }
        elseif ($this->requestMethod == 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        } elseif ($this->requestMethod == 'PUT' || $this->requestMethod == 'PATCH') {
            mb_parse_str(file_get_contents('php://input'), $body);

            return $body;
        }

        return null;
    }

    public function validate(array $data): bool
    {
        $validator = new Validator();
        $errors = $validator->validate($data);

        if (count($errors) > 0) {
            return json([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
        }

        return true;
    }

    public function get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if (isset($this->getBody()[$name])) {
            return $this->getBody()[$name];
        }

        return null;
    }

    public function __get(string $name): mixed
    {
        return $this->get($name);
    }
}