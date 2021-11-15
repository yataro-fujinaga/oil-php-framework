<?php

use App\Providers\ApplicationServiceProvider;
use App\Providers\ControllerServiceProvider;
use App\Providers\MiddlewareServiceProvider;

return [
    /**
     * add ServiceProviders.
     */
    ApplicationServiceProvider::class,
    ControllerServiceProvider::class,
    MiddlewareServiceProvider::class
];
