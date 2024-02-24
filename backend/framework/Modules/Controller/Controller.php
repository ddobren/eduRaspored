<?php

declare(strict_types=1);

class Controller
{
    protected static function stringCallback($callback): void
    {
        ControllerString::parseControllerCallback($callback);
    }

    protected static function arrayCallback($callback): void
    {
        ControllerArray::parseControllerCallback($callback);
    }

    protected static function callableCallback($callback): void
    {
        ControllerCallable::parseControllerCallback($callback);
    }
}
