<?php

namespace Framework\Routing;

use Framework\Validation\Validator;

class Call
{
    public function anonymous(\Closure $callback, array $params)
    {
        $callbackParams = (new \ReflectionFunction($callback))->getParameters();

        $request = $this->getRequest($callbackParams);

        if ($request !== null) {
            array_unshift($params, $request);
        }

        return call_user_func_array($callback, $params);
    }

    public function action(array $callback, array $params)
    {
        $controller = $callback[0];
        $action = $callback[1];

        if (class_exists($controller)) {
            $callbackParams = (new \ReflectionMethod($controller, $action))->getParameters();

            $request = $this->getRequest($callbackParams);

            if ($request !== null) {
                array_unshift($params, $request);
            }

            return call_user_func_array([new $controller(), $action], $params);
        }
    }

    public function getRequest($callbackParams)
    {
        foreach ($callbackParams as $param) {
            // return request class
            if ($param->getClass() && $param->getClass()->getParentClass() === false) {
                $request = $param->getClass()->getName();

                return new $request;
            }
            // return user`s request class
            if ($param->getClass() && $param->getClass()->getParentClass() === 'Framework\Requst') {
                $request = $param->getClass()->getName();
                $request= new $request();

                $response = call_user_func([$request, 'authorize']);

                if ($response === false)
                    $request->authorizationFailed();

                $rules = call_user_func([$request, 'rules']);
                $request->validate($rules);

                return $request;
            }
        }
    }
}