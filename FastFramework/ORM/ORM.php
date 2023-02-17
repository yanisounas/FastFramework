<?php
declare(strict_types=1);

namespace FastFramework\ORM;

use Exception;
use ReflectionClass;

class ORM
{
    private readonly Database $db;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->db = Database::getInstance($_ENV["DB_HOST"], $_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"], $_ENV["DB_NAME"], $_ENV["DB_CHARSET"], $_ENV["DB_PORT"]);
    }

    private function _getEntityReflect(string $entityName): ReflectionClass
    {
        $namespace = "";
    }
}