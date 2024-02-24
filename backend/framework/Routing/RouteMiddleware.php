<?php

class RouteMiddleware extends RouteRegistrar
{
    public static function setMiddleware($middleware)
    {
        $middleware = self::middlewareValidation($middleware);

        $lastIndex = array_key_last(parent::$registeredRoutes);

        /*if (!isset(self::$routes[$lastIndex])) {
            System::dropError("No routes found in the RouteMap.");
        }*/

        parent::$registeredRoutes[$lastIndex]["middleware"] = $middleware;
    }

    private static function middlewareValidation($middleware)
    {
        switch (true) {
            case empty($middleware):
                System::dropError("Middleware cannot be empty.");
                break;
            case !isset($middleware):
                System::dropError("Middleware cannot be null.");
                break;
            case !is_string($middleware) && !is_array($middleware):
                System::dropError("Middleware must be a string or an array.");
                break;
            default:
                return $middleware;
        }
    }
}
