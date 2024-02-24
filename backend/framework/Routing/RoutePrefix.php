<?php

class RoutePrefix extends RouteRegistrar
{
    public function setPrefix(string $prefix)
    {
        $prefix = self::prefixValidation($prefix);

        $lastIndex = array_key_last(parent::$registeredRoutes);

        /*if (!isset(self::$routes[$lastIndex])) {
            System::dropError("No routes found in the RouteMap.");
        }*/

        parent::$registeredRoutes[$lastIndex]["prefix"] = $prefix;

        foreach (parent::$registeredRoutes as &$route) {
            if (isset($route["prefix"]) && strpos($route["path"], $route["prefix"]) !== 0) {
                $route["path"] = "{$route["prefix"]}{$route["path"]}";
            }
        }
    }

    private static function prefixValidation(string $prefix): string
    {
        switch (true) {
            case empty($prefix):
                System::dropError("Prefix cannot be empty.");
            case !isset($prefix):
                System::dropError("Prefix cannot be null.");
            case !is_string($prefix):
                System::dropError("Prefix must be a string.");
            case strlen($prefix) > 255:
                System::dropError("Prefix cannot be longer than 255 characters.");
            case strlen($prefix) < 1:
                System::dropError("Prefix cannot be shorter than 1 character.");
            default:
                $prefix = "/" . trim($prefix, "/");
                return $prefix;
        }
    }
}
