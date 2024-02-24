<?php

declare(strict_types=1);

class Paths
{
    public static function rootPath(): string
    {
        return htmlspecialchars($_SERVER["DOCUMENT_ROOT"], ENT_QUOTES, 'UTF-8');
    }

    public static function publicPath(): string
    {
        return self::rootPath() . DIRECTORY_SEPARATOR . "public";
    }

    public static function appPath(): string
    {
        return self::rootPath() . DIRECTORY_SEPARATOR . "app";
    }

    public static function frameworkPath(): string
    {
        return self::rootPath() . DIRECTORY_SEPARATOR . "framework";
    }

    public static function selfPath(): string
    {
        return htmlspecialchars($_SERVER["REQUEST_URI"], ENT_QUOTES, 'UTF-8');
    }
}
