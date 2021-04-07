<?php
declare(strict_types = 1);

namespace Packages\Domain\Model\Sample;

interface SampleRepository
{
    public function findById(SampleId $id): Sample;

    public function store(Sample $sample): void;
}