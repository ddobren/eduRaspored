<?php

class App
{
    public function instance(callable $resolver)
    {
        echo call_user_func($resolver);
    }
}
