<?php

class RouteAction extends Controller
{
    public static array $matchedRoute;

    protected static function handleMatchedRoute(array $route): void
    {
        $callback = $route["callback"];
        self::$matchedRoute = $route;

        if (is_string($callback)) {
            parent::stringCallback($callback);
        } elseif (is_array($callback)) {
            parent::arrayCallback($callback);
        } elseif (is_callable($callback)) {
            parent::callableCallback($callback);
        } else {
            System::dropError("Unsupported callback type");
        }

        self::handleMiddlewares();
    }

    private static function handleMiddlewares()
    {
        if (!empty(self::$matchedRoute["middleware"])) {
            (new Middleware)->checkMiddlewareType(self::$matchedRoute["middleware"]);
        }
    }
}
