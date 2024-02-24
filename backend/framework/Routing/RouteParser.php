<?php

class RouteParser extends RouteRegistrar
{
    protected static function parseRoutes()
    {
        // kasnije dodat optimizaciju (keširanje)

        foreach (parent::$registeredRoutes as &$route) {
            RouteCompiler::compileRoute($route);
        }
    }
}
