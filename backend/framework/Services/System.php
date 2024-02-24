<?php

declare(strict_types=1);

class System
{
    public static function dropError(string $errorValue): void
    {
        self::dropMessage($errorValue, "#f56565", "#e53e3e");
    }

    public static function dropInfo(string $infoValue): void
    {
        self::dropMessage($infoValue, "#4a5568", "#2d3748");
    }

    public static function dropSuccess(string $successValue): void
    {
        self::dropMessage($successValue, "#38a169", "#2f855a");
    }

    private static function dropMessage(string $message, string $bgColor, string $borderColor): void
    {
        $html = sprintf(
            '<div style="background-color: %s; letter-spacing: 1px; color: #fff; border: 2px solid %s; border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 1rem; font-family: Arial, sans-serif; font-size: 1rem; line-height: 1.5;">%s</div>',
            htmlspecialchars($bgColor, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($borderColor, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
        );

        echo $html;

        exit();
    }
}
