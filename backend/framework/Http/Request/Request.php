<?php

declare(strict_types=1);

class Request
{
    public static function getRequestPath(): string
    {
        $requestUri = $_SERVER["REQUEST_URI"] ?? "/";
        $requestUri = preg_replace("#/+#", "/", $requestUri);
        $requestUri = urldecode($requestUri);
        $urlErrors = array_filter(["%00", "%"], fn ($item) => str_contains($requestUri, $item));

        if (!empty($urlErrors)) {
            throw new \Exception("Invalid characters in path");
        }

        $requestPath = parse_url($requestUri, PHP_URL_PATH);

        if ($requestPath === false) {
            throw new \Exception("Error in path");
        }

        if ($requestPath === "/") {
            return $requestPath;
        }

        return rtrim($requestPath, "/");
    }

    public static function getRequestMethod(): string
    {
        // Parent::sanitizeAllStuff();
        $requestMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";
        return strtoupper($requestMethod);
    }
}
