<?php

declare(strict_types=1);

// ...

require "framework/App.php";

// ...

require "framework/Runtime.php";

// ...

(new App)->instance(function () {

    require_once "Routes/api.php";

    (new Router)->start();
});
