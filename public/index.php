<?php

use App\Http\HttpKernel;
use App\Http\Message\Request;

/**
 * Register the Auto Loader
 */
require "../vendor/autoload.php";

/**
 * Select a routing file
 */
//$router = require "../routes/web.php";
$router = require "../routes/api.php";

$kernel = new HttpKernel($router);

$kernel->handle(new Request())->send();