<?php
declare(strict_types = 1);

namespace Packages\Application\Sample;

use Packages\Domain\Model\Sample\SampleRepository;

class SampleUseCase implements SampleUseCaseInterface
{
    public function __construct(private SampleRepository $repository)
    {
    }

    final public function handle(): void
    {
        // TODO: Implement handle() method.
    }
}
