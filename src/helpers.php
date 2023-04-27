<?php

use \Framework\Http\Responses\JsonReponse;
use \Framework\Request;

function base_path(string $path) {
    return BASE_PATH . "$path";
}

function dump(mixed $data)
{
    var_dump($data);
}

function dd(mixed $data)
{
    dump($data);
    die();
}

function request(): Request
{
    return new Request();
}

function json(mixed $data, int $code = 200): string
{
    $json = new JsonReponse();
    $json->setStatusCode($code)
         ->setData($data);

    echo $json->getData();
    die();
}

function env($value, $default = null) {
    if (getenv($value)) {
        return getenv($value);
    }

    return $default;
}