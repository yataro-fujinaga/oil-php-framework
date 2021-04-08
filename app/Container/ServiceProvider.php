<?php
declare(strict_types = 1);

namespace App\Container;

use Psr\Container\ContainerInterface;

abstract class ServiceProvider
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * Register dependencies.
     */
    abstract public function register(): void;
}
