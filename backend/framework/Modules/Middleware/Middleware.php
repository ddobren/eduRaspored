<?php

declare(strict_types=1);

class Middleware
{
    public function checkMiddlewareType(mixed $middleware): void
    {
        if (is_string($middleware)) {
            MiddlewareString::loadModelFile($middleware);
        } elseif (is_array($middleware)) {
            MiddlewareArray::loadModelFile($middleware);
        } else {
            System::dropError("Unsupported middleware type");
        }
    }
}
