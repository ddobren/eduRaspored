<?php

declare(strict_types=1);

class Debug
{
    public static function dd(mixed ...$values): void
    {
        self::dumpValues(...$values);
        
        die();
    }

    public static function dc(mixed ...$values): void
    {
        self::dumpValues(...$values);
    }

    public static function info(mixed ...$values): void
    {
        self::printValues(...$values);
    }

    private static function dumpValues(mixed ...$values): void
    {
        echo "<pre>";
        foreach ($values as $value) {
            var_dump($value);
        }
        echo "</pre>";
    }

    private static function printValues(mixed ...$values): void
    {
        echo "<pre>";
        foreach ($values as $value) {
            print_r($value);
        }
        echo "</pre>";
    }
}
