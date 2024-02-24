<?php

class RouteExecutor extends RouteAction
{
    protected static array $customParameters = [];

    public static function executeRoutes()
    {
        $requestMethod = Request::getRequestMethod();
        $requestPath = Request::getRequestPath();

        foreach (RouteRegistrar::$registeredRoutes as $route) {
            if ($route['method'] === $requestMethod) {
                if (self::hasParameters($route['path'])) {
                    if (self::handleParameterizedRoute($route['path'], $requestPath, $route)) {
                        return;
                    }
                } else {
                    if (self::handleNormalRoute($route['path'], $requestPath, $route)) {
                        return;
                    }
                }
            }
        }

        Response::notFound404();
    }

    private static function hasParameters(string $path): bool
    {
        return strpos($path, '{') !== false;
    }

    private static function handleParameterizedRoute(string $routePath, string $requestPath, array $route): bool
    {
        $pattern = self::generatePattern($routePath);
        if ($pattern === false) {
            $supportedTypes = implode(', ', self::getSupportedParameterTypes());
            System::dropError("Invalid route regex for path: {$routePath}. Supported types: {$supportedTypes}");
            return false;
        }

        if (preg_match($pattern, $requestPath, $matches)) {
            $parameters = self::extractParameters($routePath, $matches);
            self::setRouteParameters($route, $parameters);
            parent::handleMatchedRoute($route);
            return true;
        }
        return false;
    }

    private static function handleNormalRoute(string $routePath, string $requestPath, array $route): bool
    {
        if ($routePath === $requestPath) {
            parent::handleMatchedRoute($route);
            return true;
        }
        return false;
    }

    private static function generatePattern(string $path)
    {
        $pattern = preg_replace_callback('/\{([^:\/]+)(:([^:\/]+))?\}/', function ($matches) {
            $paramName = $matches[1];
            $paramType = isset($matches[3]) ? $matches[3] : 'string';

            if (!self::isValidParameterType($paramType)) {
                $supportedTypes = implode(', ', self::getSupportedParameterTypes());
                System::dropError("Invalid parameter regex type: {$paramType}. Supported types: {$supportedTypes}");
                return false;
            }

            $regex = self::getParameterRegex($paramType);
            return "(?P<{$paramName}>{$regex})";
        }, $path);

        return $pattern !== false ? "@^{$pattern}$@u" : false;
    }

    private static function isValidParameterType(string $type): bool
    {
        return in_array($type, self::getSupportedParameterTypes());
    }

    private static function getSupportedParameterTypes(): array
    {
        return ['int', 'string', 'uuid', 'bool', 'slug', 'date'];
    }

    private static function getParameterRegex(string $type): string
    {
        switch ($type) {
            case 'int':
                return '\d+';
            case 'string':
                return '[^/]+';
            case 'uuid':
                return '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}';
            case 'bool':
                return '(true|false|1|0)';
            case 'slug':
                return '[a-z0-9-]+';
            case 'date':
                return '\d{4}-\d{2}-\d{2}';
            default:
                return '[^/]+';
        }
    }

    private static function extractParameters(string $path, array $matches): array
    {
        preg_match_all('/\{([^:\/]+)(:([^:\/]+))?\}/', $path, $paramNames);
        $paramNames = $paramNames[1];

        $parameters = [];
        foreach ($paramNames as $index => $paramName) {
            $parameters[$paramName] = $matches[$index + 1];
        }

        return $parameters;
    }

    public static function setRouteParameters(array &$route, array $parameters = []): void
    {
        $route['parameters'] = $parameters;
    }
}
