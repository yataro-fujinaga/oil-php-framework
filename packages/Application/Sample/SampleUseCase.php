<?php
declare(strict_types = 1);

namespace Packages\Application\Sample;

class SampleUseCase implements SampleUseCaseInterface
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function handle(UseCaseRequest $request): UseCaseResponse
    {
        // TODO: Implement handle() method.
    }
}