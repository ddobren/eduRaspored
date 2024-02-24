<?php

declare(strict_types=1);

class Response
{
    public static function setStatus(int $code): void
    {
        http_response_code($code);
    }

    public static function setHeader(mixed $name, mixed $value): void
    {
        header("$name: $value");
    }

    public static function setJsonContent(array $data): void
    {
        ob_start();
        ob_clean();

        header_remove();

        header("Content-Type: application/json");
        echo json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
    
    public static function setHtmlContent(string $content, string $css = ""): void
    {
        header("Content-Type: text/html");
        echo sprintf("<html><head><style>%s</style></head><body>%s</body></html>", $css, $content);
    }

    public static function setXmlContent(string $content): void
    {
        header("Content-Type: application/xml");
        echo $content;
    }

    public static function redirect(string $url): void
    {
        header("Location: $url", true, 301);
        exit();
    }

    public static function notFound404(): void
    {
        self::setStatus(404);
        self::setJsonContent(["message" => "Not found", "code" => 404]);
    }
}
