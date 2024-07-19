<?php
declare(strict_types=1);

namespace FastFramework\ORM;

use FastFramework\ORM\Exceptions\ORMException;

class Database
{
    private readonly \PDO $db;
    private readonly QueryBuilder $builder;

    private static ?Database $instance = null;

    /**
     * @throws ORMException
     */
    public function __construct(private array $opts)
    {
        $requiredOpts = ["DB_HOST", "DB_USERNAME", "DB_PASSWORD", "DB_NAME"];
        foreach ($requiredOpts as $required)
        {
            if (
                !isset($this->opts[$required]) ||
                ($required != "DB_PASSWORD" && empty($this->opts[$required]))
            )
                throw new ORMException("$required is missing, check the .env.exemple file to see how to configure the database environment");
        }

        $this->opts["DB_CHARSET"] = $this->opts["DB_CHARSET"] ?? "utf8";
        $this->opts["DB_PORT"] = $this->opts["DB_PORT"] ?? 3306;

        $this->_connect();
        $this->builder = QueryBuilder::getInstance($this->db);
    }

    /**
     * @throws ORMException
     */
    public static function getInstance(array $opts): Database
    {
        return self::$instance ?? (self::$instance = new Database($opts));
    }

    public function __call(string $name, array $arguments): mixed
    {
        return call_user_func_array([$this->builder, $name], $arguments);
    }

    public function builder(): QueryBuilder { return $this->builder; }

    /**
     * @throws ORMException
     */
    public function _connect(): void
    {
        $dsn = sprintf(
            "mysql:host=%s;port=%d;dbname=%s;charset=%s",
            $this->opts["DB_HOST"],
            $this->opts["DB_PORT"],
            $this->opts["DB_NAME"],
            $this->opts["DB_CHARSET"]
        );

        try
        {
            $this->db = new \PDO($dsn, $this->opts["DB_USERNAME"], $this->opts["DB_PASSWORD"]);
        }
        catch (\PDOException $e)
        {
            throw new ORMException("Connection failed: " . $e->getMessage());
        }
    }
}