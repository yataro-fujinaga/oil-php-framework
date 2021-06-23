<?php
declare(strict_types = 1);

use App\Http\HttpKernel;
use App\Http\Message\Request;

/**
 * Register the Auto Loader
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Register Routing.
 */
require __DIR__ . '/../routes/api.php';

/**
 * Set error and exception handlers.
 */
require __DIR__ . '/../bootstrap/handler.php';

$container = require __DIR__ . '/../bootstrap/container.php';

$kernel = new HttpKernel(router($container));

$response = $kernel->handle(new Request());

$response->send();
