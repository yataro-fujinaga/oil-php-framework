<?php
declare(strict_types = 1);

namespace App\Container;

/**
 * Class Definition
 *
 * @package App\Container
 */
class Definition
{
    /**
     * @var mixed[]
     */
    private array $arguments = [];

    public function __construct(private string $id, private string $concrete)
    {
    }

    /**
     * @param mixed $argument
     *
     * @return $this
     */
    final public function addArgument(mixed $argument): self
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * @return string
     */
    final public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    final public function concrete(): string
    {
        return $this->concrete;
    }

    /**
     * @return mixed[]
     */
    final public function arguments(): array
    {
        return $this->arguments;
    }
}
