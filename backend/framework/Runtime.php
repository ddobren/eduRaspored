<?php

declare(strict_types=1);

// ...
require "Http/Response/Response.php";
require "Http/Request/Request.php";
// ...

// ...
require "Services/System.php";
require "Services/Debug.php";
require "Services/Paths.php";
require "Services/Constants.php";
// ...

// ...
require "framework/Modules/Model/Model.php";
// ...

// ... 
require "framework/Modules/Middleware/MiddlewareString.php";
require "framework/Modules/Middleware/MiddlewareArray.php";
require "framework/Modules/Middleware/Middleware.php";
// ...

// ...
require "framework/Modules/Controller/Controller.php";
require "framework/Modules/Controller/ControllerString.php";
require "framework/Modules/Controller/ControllerCallable.php";
require "framework/Modules/Controller/ControllerArray.php";
// ...

// ...
require "framework/Routing/RouteRegistrar.php";
require "framework/Routing/RouteCompiler.php";
require "framework/Routing/RouteParser.php";
require "framework/Routing/Router.php";
require "framework/Routing/RoutePrefix.php";
require "framework/Routing/RouteSuffix.php";
require "framework/Routing/Route.php";
require "framework/Routing/RouteMiddleware.php";
require "framework/Routing/RouteAction.php";
require "framework/Routing/RouteExecutor.php";
// ...

// ...
require "vendor/autoload.php";
