<?php
declare(strict_types = 1);

namespace App\Providers;

use App\Container\ServiceProvider;
use App\Http\Controller\Api\Sample\SampleController;
use Packages\Application\Sample\SampleUseCaseInterface;

/**
 * Class ControllerServiceProvider
 *
 * @package App\Providers
 */
class ControllerServiceProvider extends ServiceProvider
{
    final public function register(): void
    {
        $this->container->add(SampleController::class)
            ->addArgument(SampleUseCaseInterface::class);
    }
}