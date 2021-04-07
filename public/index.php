<?php
declare(strict_types = 1);

use App\Container\Container;
use App\Http\Controller\Api\Sample\SampleController;
use App\Http\HttpKernel;
use App\Http\Message\Request;
use App\Http\Middleware\SampleGlobalMiddleware;
use Packages\Application\Sample\SampleUseCase;
use Packages\Application\User\Create\SampleUseCaseInterface;
use Packages\Domain\Model\Sample\SampleRepository;
use Packages\Port\Adapter\Infrastructure\Sample\SampleRepositoryImpl;

/**
 * Register the Auto Loader
 */
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../routes/api.php';

$container = new Container();

$container->add(SampleController::class)->addArgument(SampleUseCaseInterface::class);

$container->add(SampleUseCaseInterface::class, SampleUseCase::class)->addArgument(SampleRepository::class);

$container->add(SampleRepository::class, SampleRepositoryImpl::class);

$container->add(SampleGlobalMiddleware::class);

$kernel = new HttpKernel(router($container));

$response = $kernel->handle(new Request());

$response->send();
