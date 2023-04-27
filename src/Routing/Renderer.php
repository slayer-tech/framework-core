<?php

namespace Framework\Routing;

class Renderer
{
    static public function render(mixed $response)
    {
        if (is_array($response) || is_object($response)) {
            echo json($response);
        } else {
            echo $response;
        }
    }
}