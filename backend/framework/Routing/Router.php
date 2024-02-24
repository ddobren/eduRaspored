<?php

class Router extends RouteParser
{
    public function start()
    {
        parent::parseRoutes();

        RouteExecutor::executeRoutes();
    }
}
