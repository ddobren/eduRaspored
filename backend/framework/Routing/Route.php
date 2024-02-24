<?php

class Route extends RouteRegistrar
{
    const SUPPORTED_HTTP_METHODS = ["GET", "POST", "PUT", "PATCH", "DELETE"];

    /**
     * Retrieves an array containing the supported HTTP methods.
     *
     * @return array The supported HTTP methods.
     */
    public static function supportedHttpMethods(): array
    {
        return self::SUPPORTED_HTTP_METHODS;
    }

    /**
     * Register a new route for the HTTP GET method.
     *
     * @param string $path The path of the route.
     * @param mixed $callback The callback function for the route.
     * @return Route The newly created Route object.
     */
    public static function get(string $path, $callback): Route
    {
        parent::registerNewRoute("GET", $path, $callback);

        return new self(); // Return the current instance of Route.
    }

    /**
     * Register a new route for the HTTP POST method.
     *
     * @param string $path The path of the route.
     * @param mixed $callback The callback function for the route.
     * @return Route The newly created Route object.
     */
    public static function post(string $path, $callback): Route
    {
        parent::registerNewRoute("POST", $path, $callback);

        return new self(); // Return the current instance of Route.
    }

    /**
     * Register a new route for the HTTP PUT method.
     *
     * @param string $path The path of the route.
     * @param mixed $callback The callback function for the route.
     * @return Route The newly created Route object.
     */
    public static function put(string $path, $callback): Route
    {
        parent::registerNewRoute("PUT", $path, $callback);

        return new self(); // Return the current instance of Route.
    }

    /**
     * Register a new route for the HTTP PATCH method.
     *
     * @param string $path The path of the route.
     * @param mixed $callback The callback function for the route.
     * @return Route The newly created Route object.
     */
    public static function patch(string $path, $callback): Route
    {
        parent::registerNewRoute("PATCH", $path, $callback);

        return new self(); // Return the current instance of Route.
    }

    /**
     * Register a new route for the HTTP DELETE method.
     *
     * @param string $path The path of the route.
     * @param mixed $callback The callback function for the route.
     * @return Route The newly created Route object.
     */
    public static function delete(string $path, $callback): Route
    {
        parent::registerNewRoute("DELETE", $path, $callback);

        return new self(); // Return the current instance of Route.
    }

    /**
     * Register a new route for the HTTP OPTIONS method.
     *
     * @param string $path The path of the route.
     * @param mixed $callback The callback function for the route.
     * @return Route The newly created Route object.
     */
    public static function options(string $path, $callback): Route
    {
        parent::registerNewRoute("OPTIONS", $path, $callback);

        return new self(); // Return the current instance of Route.
    }

    /**
     * Sets the prefix for the route.
     *
     * @param string $prefix The prefix to be set for the route.
     * @return Route The updated Route object.
     */
    public function prefix(string $prefix): Route
    {
        (new RoutePrefix)->setPrefix($prefix);

        return $this;
    }

    /**
     * Sets the suffix for the route.
     *
     * @param string $suffix The suffix to be set for the route.
     * @return Route The updated Route object.
     */
    public function suffix(string $suffix): Route
    {
        (new RouteSuffix)->setSuffix($suffix);

        return $this;
    }

    /**
     * Middleware function that sets the middleware for the route.
     *
     * @param $middleware The middleware to be set for the route.
     * @return Route The updated Route object.
     */
    public function middleware($middleware): Route
    {
        (new RouteMiddleware)->setMiddleware($middleware);

        return $this;
    }
}
