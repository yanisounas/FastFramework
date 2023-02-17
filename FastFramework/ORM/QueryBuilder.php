<?php
declare(strict_types=1);

namespace FastFramework\ORM;

use Exception;
use FastFramework\ORM\Exceptions\ORMException;
use JetBrains\PhpStorm\Deprecated;
use PDO;
use PDOException;
use PDOStatement;

class QueryBuilder
{

    private \PDOStatement|string $query;
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
     */
    public function select(string $table, array $columns = ['*']): QueryBuilder
    {
        $this->method = QueryMethod::SELECT;
        $columns = implode(', ', $columns);
        $this->query = "SELECT $columns FROM $table";
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function delete(string $table): QueryBuilder
    {
        $this->method = QueryMethod::DELETE;
        $this->query = "DELETE FROM $table";
        return $this;
    }

    /**
     * @param string $table
     * @param array $values
     * @return $this
     */
    public function update(string $table, array $values): QueryBuilder
    {
        $this->method = QueryMethod::UPDATE;
        $this->query = "UPDATE $table SET ";

        $datas = [];
        foreach ($values as $key => $value)
            $datas[] = "$key=?";

        $this->query .= implode(', ', $datas);
        $this->values = array_merge(($this->values ?? []), array_values($values));

        return $this;
    }

    /**
     * @param string $table
     * @param array $values
     * @return $this
     */
    public function insert(string $table, array $values): QueryBuilder
    {
        $this->method = QueryMethod::INSERT;

        $columns = implode(', ', array_keys($values));
        $prepared = implode(', ', array_fill(0, count($values), '?'));
        $this->query = "INSERT INTO `$table`($columns) VALUES($prepared)";

        $this->values = array_values($values);
        return $this;
    }

    /**
     * @param array|null $where_data
     * @param mixed ...$data
     * @return $this
     */
    public function where(?array $where_data = null, mixed ...$data): QueryBuilder
    {
        $where_data ??= $data;
        $this->where = " WHERE ";

        $datas = [];
        foreach ($where_data as $key => $value)
            $datas[] = "$key=?";

        $this->where .= implode(', ', $datas);
        $this->values = array_merge(($this->values ?? []), array_values($where_data));

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    private function _where(): QueryBuilder
    {
        if (!is_null($this->where)) $this->query .= $this->where;

        return $this;
    }

    /**
     * @param array $order_data arr[columns] or arr[columns, order (default: ASC)]
     * @return QueryBuilder
     */
    public function orderBy(array $order_data): QueryBuilder
    {
        $this->order = " ORDER BY ";
        $temp = [];

        if(array_keys($order_data) !== range(0, count($order_data) - 1))
        {
            foreach ($order_data as $k => $v)
            {
                if (gettype($k) == 'integer')
                {
                    $k = $v;
                    $v = "ASC";
                }

                $temp[] = "$k $v";
            }
        }
        else
        {
            foreach ($order_data as $data)
            {
                $temp[] = "$data ASC";
            }
        }

        $this->order .= implode(', ', $temp);
        return $this;
    }

    /**
     * @return QueryBuilder
     */
    private function _order(): QueryBuilder
    {
        if (!is_null($this->order)) $this->query .= $this->order;

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
     * @return $this
     */
    public function _limit(): QueryBuilder
    {
        if (!is_null($this->limit)) $this->query .= $this->limit;

        return $this;
    }

    /**
     * @throws ORMException
     */
    public function exec(): QueryBuilder
    {
        try
        {
            if (!$this->method || empty($this->query)) throw new ORMException("You have to build a query before calling this method");
            if (($this->method === QueryMethod::UPDATE || $this->method === QueryMethod::DELETE) && is_null($this->where)) throw new ORMException($this->method->name . " need a where statement");

            $this->_where()->_order()->_limit();


            if ($this->values) array_walk($this->values, function (&$item) { $item = htmlspecialchars($item); });

            $this->query = $this->db->prepare($this->query);
            $this->query->execute($this->values);

            $this->values = null;
            $this->where = null;
            $this->limit = null;

            if ($this->method !== QueryMethod::SELECT && $this->method !== QueryMethod::CUSTOM)
                $this->method = null;
        }
        catch (\PDOException $e) {die(throw new \PDOException($e->getMessage()));}
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
}