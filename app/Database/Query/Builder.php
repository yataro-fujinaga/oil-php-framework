<?php
declare(strict_types = 1);

namespace App\Database\Query;

use App\Database\PDO\DB;

/**
 * Class Builder
 *
 * @package App\Database\Query
 */
class Builder
{
    private const STATEMENT_FORMAT = '%s%s%s';

    private DB     $db;
    private array  $where = [];
    private string $table = '';
    private array  $columns = [];

    private function __construct()
    {
        $this->db = new DB([
            'dsn'      => 'mysql:dbname=sample_db;host=mysql',
            'user'     => 'root',
            'password' => 'password',
            'options'  => []
        ]);
    }

    final public static function newQuery(string $table): self
    {
        return (new self())->from($table);
    }

    final public function where(string $columnName, string $operator, mixed $value): self
    {
        $this->where[] = [
            'column'   => $columnName,
            'operator' => $operator,
            'value'    => $value
        ];

        return $this;
    }

    final public function from(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    final public function select(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    final public function get(): array
    {
        $sql = sprintf('SELECT %s FROM %s WHERE %s',
            $this->buildSelectStatement(),
            $this->table,
            $this->buildWhereStatement()
        );

        return $this->db->fetch($sql, $this->buildParams()) ?: [];
    }

    final public function getAll(): array
    {
        $sql = sprintf('SELECT %s FROM %s WHERE %s',
            $this->buildSelectStatement(),
            $this->table,
            $this->buildWhereStatement()
        );

        return $this->db->fetchAll($sql, $this->buildParams()) ?: [];
    }

    final public function insert(array $params): void
    {
        $sql = sprintf(
            'INSERT INTO %s SET %s',
            $this->table,
            $this->buildStatement($params)
        );

        $this->db->fetch($sql, $this->buildParams($params));
    }

    final public function update(array $params): void
    {
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $this->table,
            $this->buildStatement($params),
            $this->buildWhereStatement()
        );

        $this->db->fetch($sql, $this->buildParams($params));
    }

    final public function delete(): void
    {
        $sql = sprintf(
            'DELETE FROM %s WHERE %s',
            $this->table,
            $this->buildWhereStatement()
        );

        $this->db->fetch($sql, $this->buildParams());
    }

    /**
     * @return string
     */
    private function buildSelectStatement(): string
    {
        if ($this->columns === []) {
            return '*';
        }

        $statement = '';

        foreach ($this->columns as $column) {
            $statement .= sprintf('%s, ', $column);
        }

        return trim($statement, ', ');
    }


    private function buildStatement(array $params): string
    {
        $statement = '';

        foreach ($params as $key => $value) {
            $statement .= sprintf(
                self::STATEMENT_FORMAT,
                $key,
                '=',
                $this->createPlaceholder($key)
            );
            $statement .= ', ';
        }

        return trim($statement, ', ');
    }

    /**
     * @return string
     */
    private function buildWhereStatement(): string
    {
        $statement = '';

        foreach ($this->where as $condition) {
            $statement .= sprintf(
                self::STATEMENT_FORMAT,
                $condition['column'],
                $condition['operator'],
                $this->createWherePlaceholder($condition['column'])
            );

            // @todo OR対応
            $statement .= ' AND ';
        }

        return trim($statement, 'AND ');
    }

    private function buildParams(array $params = []): array
    {
        $whereParams = array_merge(...array_map(function (array $condition) {
            return [$this->createWherePlaceholder($condition['column']) => $condition['value']];
        }, $this->where));

        $updateParams = [];

        foreach ($params as $key => $value) {
            $updateParams[$this->createPlaceholder($key)] = $value;
        }

        return array_merge($whereParams, $updateParams);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function createWherePlaceholder(string $key): string
    {
        return $this->createPlaceholder($key, 'w_');
    }

    /**
     * @param string $prefix
     * @param string $key
     *
     * @return string
     */
    private function createPlaceholder(string $key, string $prefix = ''): string
    {
        return ':' . $prefix . $key;
    }
}
