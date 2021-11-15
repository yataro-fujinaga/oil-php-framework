<?php
declare(strict_types = 1);

namespace App\Providers;

use Packages\Application\Sample\SampleUseCase;
use Packages\Application\Sample\SampleUseCaseInterface;
use Packages\Domain\Model\Sample\SampleRepository;
use Packages\Port\Adapter\Infrastructure\Sample\SampleRepositoryImpl;
use ToyContainer\ServiceProvider;

/**
 * Class ApplicationServiceProvider
 *
 * @package App\Providers
 */
class ApplicationServiceProvider extends ServiceProvider
{
    final public function register(): void
    {
        $this->container->add(SampleUseCaseInterface::class, SampleUseCase::class)
            ->addArgument(SampleRepository::class);

        $this->container->add(SampleRepository::class, SampleRepositoryImpl::class);
    }
}
