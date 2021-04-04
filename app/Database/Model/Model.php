<?php
declare(strict_types = 1);

namespace App\Database\Model;

use App\Database\Query\Builder;

/**
 * Class Model
 *
 * @package App\Database\Model
 */
abstract class Model
{
    /**
     * @var array
     */
    private array $attributes = [];

    /**
     * @var Builder
     */
    private Builder $query;

    public function __construct()
    {
        $this->query = Builder::newQuery($this->className());
    }

    public static function where(string $column, string $operator, string $value): static
    {
        $model = new static();

        $model->query->where($column, $operator, $value);

        return $model;
    }

    final public function first(): static
    {
        foreach ($this->query->get() as $k => $v) {
            $this->{$k} = $v;
        }

        return $this;
    }

    final public function all(): array
    {
        return array_map(static function (array $result) {
            $model = new static();

            foreach ($result as $k => $v) {
                $model->{$k} = $v;
            }

            return $model;
        }, $this->query->getAll());
    }

    public static function create(array $params): void
    {
        $model = new static();

        $model->query->insert($params);
    }

    final public function save(): void
    {
        $this->query->update($this->attributes);
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name];
    }

    /**
     * @return string
     */
    private function className(): string
    {
        preg_match('/([^\\\]*?)$/', static::class, $matches);

        return strtolower($matches[1]);
    }
}
