<?php

declare(strict_types=1);

class Model
{
    private static function requireModelFile(string $modelName): void
    {
        $fullFilePath = VN_APP_PATH . DIRECTORY_SEPARATOR . "Models" . DIRECTORY_SEPARATOR . "{$modelName}.model.php";

        if (empty($modelName)) {
            System::dropError("No model name provided");
        }

        if (!file_exists($fullFilePath)) {
            System::dropError("Could not find model file: $fullFilePath");
        }

        require_once $fullFilePath;
    }

    public static function loadModel(string $modelName): void
    {
        self::requireModelFile($modelName);
    }
}
