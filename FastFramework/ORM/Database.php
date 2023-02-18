<?php
declare(strict_types=1);

namespace FastFramework\ORM;

use Exception;
use FastFramework\ORM\Exceptions\ORMException;

class Database
{
    private readonly \PDO $db;
    private readonly QueryBuilder $builder;

    private static ?Database $instance = null;

    /**
     * @throws Exception
     */
    public function __construct(private array $opts)
    {
        if (!isset($this->opts["DB_HOST"]) || empty($this->opts["DB_HOST"])) throw new ORMException("Host is missing. Set DB_HOST in .env file");
        if (!isset($this->opts["DB_USERNAME"]) || empty($this->opts["DB_USERNAME"])) throw new ORMException("Mysql username is missing. Set DB_USERNAME in .env file");
        if (!isset($this->opts["DB_PASSWORD"])) throw new ORMException("Mysql password is missing. Set DB_PASSWORD in .env file");
        if (!isset($this->opts["DB_NAME"]) || empty($this->opts["DB_NAME"])) throw new ORMException("Database is missing. Set DB_NAME in .env file");
        if (!isset($this->opts["DB_CHARSET"]) || empty($this->opts["DB_CHARSET"])) $this->opts["DB_CHARSET"] = "utf8";
        if (!isset($this->opts["DB_PORT"]) || empty($this->opts["DB_PORT"])) $this->opts["DB_PORT"] = 3306;

        $this->_connect();
        $this->builder = QueryBuilder::getInstance($this->db);
    }

    /**
     * @throws Exception
     */
    public static function getInstance(array $opts): Database
    {
        return (self::$instance !== null) ? self::$instance : (self::$instance = new Database($opts));
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call(string $name, array $arguments): mixed { return call_user_func_array([$this->builder, $name], $arguments); }

    public function builder(): QueryBuilder {return $this->builder;}

    private function _connect(): void
    {
        $this->db = new \PDO("mysql:host=".$this->opts["DB_HOST"].";port=".$this->opts["DB_PORT"].";dbname=".$this->opts["DB_NAME"].";charset=".$this->opts["DB_CHARSET"], $this->opts["DB_USERNAME"], $this->opts["DB_PASSWORD"]);
    }
}