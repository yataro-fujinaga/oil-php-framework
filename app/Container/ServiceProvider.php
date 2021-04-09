<?php
declare(strict_types = 1);

namespace App\Container;

abstract class ServiceProvider
{
    public function __construct(protected Container $container)
    {
    }

    /**
     * Register dependencies.
     */
    abstract public function register(): void;
}
