<?php

use App\Http\Message\Request;
use App\Http\Message\Response;
use App\Routing\Router;
use App\Http\Middleware\SampleRouteMiddleware;
use App\Http\Controller\Api\Sample\SampleController;

$router = new Router();

/**
 * Example of routing using Callback function.
 */
$router->map('GET', '/callable_tests/:test_id', function (Request $request, array $args = []) {
    $content    = ['message' => sprintf('test_id is %s.', $args['test_id'])];
    $statusCode = '200';
    $statusText = 'OK';
    $headers    = [];

    return new Response($content, $statusCode, $statusText, $headers);
});

/**
 * Example of routing using Class that implements the __invoke method.
 */
$router->map('GET', '/controller_tests/:test_id', SampleController::class)
    ->middleware(SampleRouteMiddleware::class);

return $router;
