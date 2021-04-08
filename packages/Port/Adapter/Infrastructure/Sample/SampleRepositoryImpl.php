<?php
declare(strict_types = 1);

namespace Packages\Port\Adapter\Infrastructure\Sample;

use Packages\Domain\Model\Sample\Sample;
use Packages\Domain\Model\Sample\SampleId;
use Packages\Domain\Model\Sample\SampleRepository;

class SampleRepositoryImpl implements SampleRepository
{

    public function findById(SampleId $id): Sample
    {
        // TODO: Implement findById() method.
    }

    public function store(Sample $sample): void
    {
        // TODO: Implement store() method.
    }
}
