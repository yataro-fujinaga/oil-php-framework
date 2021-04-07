<?php
declare(strict_types = 1);

namespace App\Container;

use App\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerInterface
{
    private array $definitions = [];

    /**
     * @param string $id
     *
     * @return object
     * @throws ReflectionException
     */
    private function resolve(string $id): object
    {
        try {
            $reflectionClass = new ReflectionClass($this->definitions[$id]);
            $constructor     = $reflectionClass->getConstructor();

            if ($constructor === null) {
                return $reflectionClass->newInstance();
            }

            $args = array_map(function (ReflectionParameter $parameter) {
                return $this->resolve((string) $parameter->getType());
            }, $constructor->getParameters());

            return $reflectionClass->newInstanceArgs($args);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundException|ReflectionException
     */
    final public function get(string $id): mixed
    {
        return $this->has($id)
            ? $this->resolve($id)
            : throw new NotFoundException("Entry '$id' not found.");
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    final public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions);
    }
}
