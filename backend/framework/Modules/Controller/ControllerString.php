<?php

class ControllerString
{
    private static function requireControllerFile(string $controllerName): void
    {
        $fullFilePath = VN_APP_PATH . DIRECTORY_SEPARATOR . "Controllers" . DIRECTORY_SEPARATOR . "{$controllerName}.controller.php";

        if (empty($controllerName)) {
            System::dropError("No controller name provided");
        }

        if (!file_exists($fullFilePath)) {
            System::dropError("Could not find controller file: $fullFilePath");
        }

        require_once $fullFilePath;
    }

    private static function executeNormalController(string $controllerData): ?object
    {
        self::requireControllerFile($controllerData);

        if (str_contains($controllerData, "/")) {
            $controller = explode("/", $controllerData);
            $controllerNewName = ucfirst(end($controller)) . "Controller";
        } else {
            $controllerNewName = ucfirst($controllerData) . "Controller";
        }

        $fullFilePath = VN_APP_PATH . DIRECTORY_SEPARATOR . "Controllers" . DIRECTORY_SEPARATOR . "{$controllerData}.controller.php";

        if (class_exists($controllerNewName)) {
            $reflection = new ReflectionClass($controllerNewName);
            if ($reflection->hasMethod('__construct')) {
                $instance = $reflection->newInstanceArgs(...RouteAction::$matchedRoute);
                return $instance;
            } else {
                System::dropError("No constructor found in class '{$controllerNewName}' in file '{$fullFilePath}'");
            }
        } else {
            System::dropError("Controller class '{$controllerNewName}' not found in file '{$fullFilePath}'");
        }

        return null;
    }

    private static function executeActionController(string $controllerData)
    {
        $controllerData = explode("@", $controllerData);

        if (str_contains($controllerData[0], "/")) {
            $controller = explode("/", $controllerData[0]);
            $controllerNewName = ucfirst(end($controller)) . "Controller";
        }

        $controllerName = isset($controllerData[0]) ? $controllerData[0] : null;
        $controllerMethod = isset($controllerData[1]) ? $controllerData[1] : null;

        if (count($controllerData) !== 2) {
            System::dropError("Invalid action controller method format");
        }

        if (empty($controllerName)) {
            System::dropError("No controller name provided");
        }

        if (empty($controllerMethod)) {
            System::dropError("No method name provided for action controller '{$controllerNewName}'");
        }

        $fullFilePath = VN_APP_PATH . "/Controllers/{$controllerName}.controller.php";

        if (file_exists($fullFilePath)) {
            self::requireControllerFile($controllerName);
        } else {
            System::dropError("Controller file '{$controllerName}.controller.php' not found in expected folder 'App/Controllers'");
        }

        if (!class_exists($controllerNewName)) {
            System::dropError("Controller class '{$controllerNewName}' not found in file '{$fullFilePath}'");
        }

        if (!method_exists($controllerNewName, $controllerMethod)) {
            System::dropError("Method '{$controllerMethod}' not found in class '{$controllerNewName}' in file '{$fullFilePath}'");
        }

        $reflection = new ReflectionClass($controllerNewName);

        if (!$reflection->isInstantiable()) {
            System::dropError("Controller class '{$controllerNewName}' cannot be instantiated");
        }

        if ($reflection->hasMethod("__construct")) {
            $constructorParameters = $reflection->getMethod("__construct")->getParameters();
            $parameters = [];

            foreach ($constructorParameters as $parameter) {
                $name = $parameter->getName();
                if (isset(RouteAction::$matchedRoute["parameters"][$name])) {
                    $parameters[] = RouteAction::$matchedRoute["parameters"][$name];
                } else {
                    System::dropError("Invalid constructor parameter: $name");
                }
            }

            return $reflection->newInstanceArgs($parameters);
        } else {
            $methodParameters = $reflection->getMethod($controllerMethod)->getParameters();
            $parameters = [];

            foreach ($methodParameters as $parameter) {
                $name = $parameter->getName();
                if (isset(RouteAction::$matchedRoute["parameters"][$name])) {
                    $parameters[] = RouteAction::$matchedRoute["parameters"][$name];
                } else {
                    System::dropError("Invalid method parameter: $name");
                }
            }

            return call_user_func_array([new $controllerNewName, $controllerMethod], $parameters);
        }
    }

    public static function parseControllerCallback(string $controllerData): void
    {
        $controllerData = ($controllerData);

        if (strpos($controllerData, "@") !== false) {
            self::executeActionController($controllerData);
        } else {
            self::executeNormalController($controllerData);
        }
    }
}
