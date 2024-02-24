<?php

class ControllerCallable
{
    public static function parseControllerCallback($callback)
    {
        self::executeCallableController($callback);
    }

    private static function executeCallableController(object $callback)
    {
        if (empty(RouteAction::$matchedRoute["parameters"])) {
            echo call_user_func($callback);
        } else {
            // TODO: Add support for constructor parameters in func args
            $reflection = new ReflectionFunction($callback);
            $parameters = [];

            $numberOfParameters = $reflection->getNumberOfParameters();

            if ($numberOfParameters > 0) {
                foreach ($reflection->getParameters() as $parameter) {
                    $name = $parameter->getName();
                    if (isset(RouteAction::$matchedRoute["parameters"][$name])) {
                        $parameters[] = RouteAction::$matchedRoute["parameters"][$name];
                    } else {
                        System::dropError("Invalid parameter: $name");
                        exit();
                    }
                }
            }

            echo call_user_func_array($callback, $parameters);
        }
    }
}
