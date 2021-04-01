<?php

use App\Routing\Router;

$router = new Router();

$router->map('GET', '/tests/:test_id', function () {
    echo 'Hello world.';
});

return $router;