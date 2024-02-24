<?php

class RouteSuffix extends RouteRegistrar
{
    public function setSuffix(string $suffix)
    {
        $suffix = self::suffixValidation($suffix);

        $lastIndex = count(parent::$registeredRoutes) - 1;

        parent::$registeredRoutes[$lastIndex]["suffix"] = $suffix;

        foreach (parent::$registeredRoutes as &$route) {
            if (isset($route["suffix"]) && strpos($route["path"], $route["suffix"]) !== 0) {
                $route["suffix"] = rtrim($route["suffix"], "/");
                $route["path"] = "{$route["path"]}{$route["suffix"]}";
            }
        }
    }

    private static function suffixValidation(string $suffix): string
    {
        switch (true) {
            case empty($suffix):
                System::dropError("Suffix cannot be empty.");
            case !isset($suffix):
                System::dropError("Suffix cannot be null.");
            case !is_string($suffix):
                System::dropError("Suffix must be a string.");
            case strlen($suffix) > 255:
                System::dropError("Suffix cannot be longer than 255 characters.");
            case strlen($suffix) < 1:
                System::dropError("Suffix cannot be shorter than 1 character.");
            default:
                return "/" . ltrim($suffix, "/");
        }
    }
}
