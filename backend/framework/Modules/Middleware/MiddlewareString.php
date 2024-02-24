<?php  

declare(strict_types=1);

class MiddlewareString
{
    private static function requireModelFile(string $middlewareName): void
    {
        $fullFilePath = VN_APP_PATH . DIRECTORY_SEPARATOR . "Middlewares" . DIRECTORY_SEPARATOR . "{$middlewareName}.middleware.php";

        if (empty($middlewareName)) {
            System::dropError("No middleware name provided");
        }

        if (!file_exists($fullFilePath)) {
            System::dropError("Could not find middleware file: $fullFilePath");
        }

        require_once $fullFilePath;
    }

    public static function loadModelFile(string $middleware)
    {
        self::requireModelFile($middleware);
    }
}
