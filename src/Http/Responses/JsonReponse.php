<?php

namespace Framework\Http\Responses;

class JsonReponse
{
    private mixed $data;

    public function __construct()
    {
        header("Content-Type: application/json");
    }

    public function setStatusCode(int $code): JsonReponse
    {
        http_response_code($code);

        return $this;
    }

    public function setData(mixed $data): JsonReponse
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): string
    {
        return json_encode($this->data);
    }
}