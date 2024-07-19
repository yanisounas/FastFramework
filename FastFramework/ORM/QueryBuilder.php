<?php
declare(strict_types=1);

namespace FastFramework\ORM;

use FastFramework\ORM\Exceptions\ORMException;
use PDO;
use PDOStatement;

class QueryBuilder
{
    private PDOStatement|string|null $query = null;
    private ?string $where = null;
    private ?string $order = null;
    private ?array $values = null;
    private ?string $limit = null;
    private ?QueryMethod $method = null;

    private static ?QueryBuilder $instance = null;

    private function __construct(private readonly PDO $db) {}

    /**
     * @param PDO|null $db
     * @return QueryBuilder
     * @throws ORMException
     */
    public static function getInstance(?PDO $db = null): QueryBuilder
    {
        if (self::$instance !== null) return self::$instance;
        if ($db === null) throw new ORMException("PDO instance is required to create a query builder");

        return (self::$instance = new QueryBuilder($db));
    }

    /**
     * @param string $query
     * @param QueryMethod $method
     * @return $this
     */
    public function setQueryString(string $query, QueryMethod $method = QueryMethod::CUSTOM): QueryBuilder
    {
        $this->method = $method;
        $this->query = $query;
        return $this;
    }

    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    /**
     * @param string $table
     * @param array $columns
     * @return $this
     * @throws ORMException
     */
    public function select(string $table, array $columns = ["*"]): QueryBuilder
    {
        $this->method = QueryMethod::SELECT;
        $this->validateIdentifier($table);
        $columns = implode(', ', ($columns == ["*"]) ? $columns : array_map(fn($col) => "`$col`", $columns));
        $this->query = "SELECT $columns FROM `$table`";
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     * @throws ORMException
     */
    public function delete(string $table): QueryBuilder
    {
        $this->method = QueryMethod::DELETE;
        $this->validateIdentifier($table);
        $this->query = "DELETE FROM `$table`";
        return $this;
    }

    /**
     * @param string $table
     * @param array $values
     * @return $this
     * @throws ORMException
     */
    public function update(string $table, array $values): QueryBuilder
    {
        $this->method = QueryMethod::UPDATE;
        $this->validateIdentifier($table);
        $datas = implode(', ', array_map(fn($key) => "`$key` = ?", array_keys($values)));
        $this->query = "UPDATE `$table` SET $datas";

        $this->values = array_merge(($this->values ?? []), array_values($values));

        return $this;
    }

    /**
     * @param string $table
     * @param array $values
     * @return $this
     * @throws ORMException
     */
    public function insert(string $table, array $values): QueryBuilder
    {
        $this->method = QueryMethod::INSERT;
        $this->validateIdentifier($table);

        $columns = implode(", ", array_map(fn($col) => "`$col`", array_keys($values)));
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->query = "INSERT INTO `$table`($columns) VALUES($placeholders)";

        $this->values = array_values($values);
        return $this;
    }

    /**
     * @param array|null $where_data
     * @param mixed ...$data
     * @return $this
     * @throws ORMException
     */
    public function where(?array $where_data = null, mixed ...$data): QueryBuilder
    {
        $where_data ??= $data;

        if ($where_data)
        {
            $conditions = implode(", ", array_map(fn($key) => "`". $this->validateIdentifier($key) ."` = ?", array_keys($where_data)));
            $this->where = " WHERE $conditions";
            $this->values = array_merge(($this->values ?? []), array_values($where_data));
        }

        return $this;
    }

    /**
     * @param array $order_data arr[columns] or arr[columns, order (default: ASC)]
     * @return QueryBuilder
     */
    public function orderBy(array $order_data): QueryBuilder
    {
        $order = implode(", ", array_map(
            fn($column, $direction) => "`$column` $direction",
            array_keys($order_data),
            $order_data
        ));
        $this->order = $order;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit = 1): QueryBuilder
    {
        $this->limit = " LIMIT $limit";
        return $this;
    }

    /**
     * @throws ORMException
     */
    public function exec(): QueryBuilder
    {
        if ($this->query === null) throw new ORMException("No query set");
        if ($this->query instanceof PDOStatement) throw new ORMException("A query is already executed, create a new query before exec");
        if (($this->method === QueryMethod::UPDATE || $this->method === QueryMethod::DELETE) && $this->where === null) throw new ORMException("Missing WHERE clause");

        $this->query .= $this->where ?? "";
        $this->query .= $this->order ?? '';
        $this->query .= $this->limit ?? '';

        try
        {
            $this->query = $this->db->prepare($this->query);
            $this->query->execute($this->values ?? []);

            $this->values = null;
            $this->where = null;
            $this->limit = null;
            $this->order = null;

            if ($this->method !== QueryMethod::SELECT && $this->method !== QueryMethod::CUSTOM)
                $this->method = null;
        }
        catch (\PDOException $e)
        {
            throw new ORMException("Query execution failed: " . $e->getMessage(), 0, $e);
        }
        return $this;
    }

    /**
     * @param int $mode
     * @param int $cursorOrientation
     * @param int $cursorOffset
     * @return mixed
     * @throws ORMException
     */
    public function fetchOne(int $mode = PDO::FETCH_BOTH, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        if (!$this->query instanceof PDOStatement) throw new ORMException("Query need to be executed first");
        if ($this->method !== QueryMethod::SELECT && $this->method !== QueryMethod::CUSTOM) throw new ORMException("Fetch not supported for ". $this->method->name);

        return $this->query->fetch($mode, $cursorOrientation, $cursorOffset);
    }

    /**
     * @param int $mode
     * @param mixed ...$args
     * @return array|false
     * @throws ORMException
     */
    public function fetchAll(int $mode = PDO::FETCH_BOTH, mixed ...$args ): array|false
    {
        if (!$this->query instanceof PDOStatement) throw new ORMException("Query need to be executed first");
        if ($this->method !== QueryMethod::SELECT && $this->method !== QueryMethod::CUSTOM) throw new ORMException("FetchAll not supported for " . $this->method->name);

        return $this->query->fetchAll($mode, ...$args);
    }

    /**
     * @throws ORMException
     */
    private function validateIdentifier(string $identifier): string
    {
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $identifier))
            throw new ORMException("Invalid identifier: $identifier");

        return $identifier;
    }
}