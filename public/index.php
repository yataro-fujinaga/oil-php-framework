<?php

use App\Http\HttpKernel;
use App\Http\Message\Request;

/**
 * Register the Auto Loader
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Select a routing file.
 */
//$router = require "../routes/web.php";
$router = require __DIR__ . '/../routes/api.php';

$kernel = new HttpKernel($router);

$response = $kernel->handle(new Request());

$response->send();
