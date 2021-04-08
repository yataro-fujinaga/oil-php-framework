<?php
declare(strict_types = 1);

namespace App\Providers;

use App\Container\ServiceProvider;
use App\Http\Middleware\SampleGlobalMiddleware;
use App\Http\Middleware\SampleRouteMiddleware;

/**
 * Class MiddlewareServiceProvider
 *
 * @package App\Providers
 */
class MiddlewareServiceProvider extends ServiceProvider
{
    final public function register(): void
    {
        $this->container->add(SampleRouteMiddleware::class);

        $this->container->add(SampleGlobalMiddleware::class);
    }
}
