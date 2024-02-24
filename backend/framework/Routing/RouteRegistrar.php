<?php

class RouteRegistrar
{
    public static array $registeredRoutes = [];

    /**
     * Sanitize the path by removing trailing slash.
     *
     * @param string $path The URL path to sanitize.
     * @return string The sanitized path.
     */
    protected static function processPath(string $path): string
    {
        if ($path === "/") {
            return $path;
        }

        return rtrim($path, "/");
    }

    /**
     * Register a new route.
     *
     * @param string $method The HTTP method of the route.
     * @param string $path The URL path of the route.
     * @param $callback The callback function or controller action for the route.
     */
    protected static function registerNewRoute(string $method, string $path, $callback)
    {
        $sanitizedPath = self::processPath($path);

        $newRegisteredRoute = [
            "method" => $method,
            "path" => $sanitizedPath,
            "callback" => $callback,
            "prefix" => null,
            "suffix" => null,
            "middleware" => null
        ];

        if (self::routeExist($method, $sanitizedPath)) {
            System::dropError("Route with method \"{$method}\" and path \"{$sanitizedPath}\" already exists.");
        } else {
            self::$registeredRoutes[] = $newRegisteredRoute;
        }
    }

    /**
     * Check if a route exists in the registered routes.
     *
     * @param string $method The HTTP method of the route.
     * @param string $path The URL path of the route.
     * @return bool Returns true if the route exists, false otherwise.
     */
    private static function routeExist(string $method, string $path)
    {
        foreach (self::$registeredRoutes as $route) {
            if ($route["method"] === $method && $route["path"] === $path) {
                return true;
            }
        }

        return false;
    }
}
