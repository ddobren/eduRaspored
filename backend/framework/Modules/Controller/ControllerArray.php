<?php

class ControllerArray
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

    private static function executeArrayController(array $controllerData)
    {
        if (count($controllerData) !== 2) {
            System::dropError("Invalid action controller method format");
        }

        $controllerName = isset($controllerData[0]) ? $controllerData[0] : null;
        $controllerMethod = isset($controllerData[1]) ? $controllerData[1] : null;
        $controllerNewName = ucfirst($controllerName) . "Controller";

        if (empty($controllerName)) {
            System::dropError("No controller name provided");
        }

        if (empty($controllerMethod)) {
            System::dropError("No method name provided for action controller '{$controllerNewName}'");
        }

        $controllerFileName = strtolower(preg_replace("/(?<!^)[A-Z]/", "-$0", $controllerName));

        $fullFilePath = VN_APP_PATH . "/Controllers/{$controllerFileName}.controller.php";

        if (file_exists($fullFilePath)) {
            self::requireControllerFile($controllerFileName);
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
            // TODO: Add support for constructor parameters in func args
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
            // TODO: Add support for $controllerMethod and constructor methods parameters in func args
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


    public static function parseControllerCallback(array $controllerData): void
    {
        $controllerData = ($controllerData);

        self::executeArrayController($controllerData);
    }
}
