<?php

class RouteCompiler
{
    public static function compileRoute(&$route)
    {
        $params = [];
        $path = $route['path'];

        if (self::hasParameters($path)) {
            preg_match_all('/\{([^:\/]+)(:([^:\/]+))?\}/', $path, $matches);

            foreach ($matches[1] as $paramName) {
                $params[$paramName] = null;
            }
        }
    }

    private static function hasParameters(string $path)
    {
        return preg_match('/\{([^:\/]+)(:([^:\/]+))?\}/', $path);
    }
}

