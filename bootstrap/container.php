<?php
declare(strict_types = 1);

$providers = require __DIR__ . '/../config/providers.php';
$container = new App\Container\Container();

foreach ($providers as $provider) {
    (new $provider($container))->register();
}

return $container;
