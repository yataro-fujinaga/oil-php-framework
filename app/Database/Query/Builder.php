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

    final public function update(array $params): void
    {
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $this->table,
            $this->buildUpdateStatement($params),
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

    /**
     * @param array $params
     *
     * @return string
     */
    private function buildUpdateStatement(array $params): string
    {
        $result = '';

        foreach ($params as $key => $value) {
            $result .= sprintf(
                self::STATEMENT_FORMAT,
                $key,
                '=',
                $this->createUpdatePlaceholder($key)
            );
            $result .= ', ';
        }

        return trim($result, ', ');
    }

    /**
     * @return string
     */
    private function buildWhereStatement(): string
    {
        $result = '';

        foreach ($this->where as $condition) {
            $result .= sprintf(
                self::STATEMENT_FORMAT,
                $condition['column'],
                $condition['operator'],
                $this->createWherePlaceholder($condition['column'])
            );

            // @todo OR対応
            $result .= ' AND ';
        }

        return trim($result, 'AND ');
    }

    private function buildParams(array $params = []): array
    {
        $whereParams = array_merge(...array_map(function (array $condition) {
            return [$this->createWherePlaceholder($condition['column']) => $condition['value']];
        }, $this->where));

        $updateParams = [];

        foreach ($params as $key => $value) {
            $updateParams[$this->createUpdatePlaceholder($key)] = $value;
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
        return $this->createPlaceholder('w_', $key);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function createUpdatePlaceholder(string $key): string
    {
        return $this->createPlaceholder('u_', $key);
    }

    /**
     * @param string $prefix
     * @param string $key
     *
     * @return string
     */
    private function createPlaceholder(string $prefix, string $key): string
    {
        return ':' . $prefix . $key;
    }
}
