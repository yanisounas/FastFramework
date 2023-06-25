<?php
declare(strict_types=1);

namespace FastFramework\ORM;

use Exception;
use FastFramework\AbstractClass\Entity;
use FastFramework\FileSystem\Utils;
use FastFramework\ORM\Exceptions\ORMException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class ORM
{
    private readonly Database $db;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->db = Database::getInstance( array_filter($_ENV, function($key) { return str_starts_with($key, "DB_"); }, ARRAY_FILTER_USE_KEY) );
    }

    /**
     * @throws ORMException
     */
    private function _getEntityReflect(string $entityName): ReflectionClass
    {
        $entityClass = (str_ends_with($entityName, "Entity")) ? $entityName : $entityName . "Entity";
        $entityNameWithNamespace = Utils::getSrcNamespace() . "Entity\\$entityClass";
        try
        {
            $reflect = new ReflectionClass($entityNameWithNamespace);
            if (!$reflect->isSubclassOf(Entity::class)) throw new ORMException("$entityNameWithNamespace must be a subclass of Entity");
            if ($reflect->getProperty("TABLE_NAME")->getValue() === null) $reflect->setStaticPropertyValue("TABLE_NAME", str_replace("Entity", "", $entityName));
            return $reflect;
        }
        catch (ReflectionException $e)
        {
            throw new ORMException("Can't create reflection for $entityNameWithNamespace\nDetails: " . $e->getMessage());
        }
    }

    /**
     * @param ReflectionProperty[] $entityProperties
     * @param array $values
     * @return void
     */
    private function _removeBadProperties(array $entityProperties, array &$values): void
    {
        array_walk($entityProperties, function (&$item) { $item = ($item instanceof ReflectionProperty) ? $item->getName() : $item; });

        $values = array_filter($values, function($key) use ($entityProperties) { return in_array($key, $entityProperties); }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @throws ORMException
     * @throws ReflectionException
     */
    public function getAll(string $entityName): array
    {
        $reflect = $this->_getEntityReflect($entityName);
        $response = $this->db->builder()->select( $reflect->getProperty("TABLE_NAME")->getValue() )->exec()->fetchAll();

       array_walk($response, function(&$item) use ($reflect)
       {
            $entity = $reflect->newInstance();
            $entity->load($item);
            $item = $entity;
       });

       return $response;
    }

    /**
     * @param string $entityName
     * @param array $columnValues
     * @param mixed ...$kwargs
     * @return object|false
     * @throws ORMException
     * @throws ReflectionException
     */
    public function getBy(string $entityName, array $columnValues = [], mixed ...$kwargs): object|false
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->_getEntityReflect($entityName);

        $result = $this->db->builder()->select( $reflect->getProperty("TABLE_NAME")->getValue() )->where($columnValues)->exec()->fetchOne();

        return ($result) ? ($reflect->newInstance())->load($result) : false;
    }

    /**
     * @param string $entityName
     * @param array $columnValues
     * @param mixed ...$kwargs
     * @return void
     * @throws ORMException
     * @throws ReflectionException
     */
    public function make(string $entityName, array $columnValues = [], mixed ...$kwargs): void
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->_getEntityReflect($entityName);
        $this->_removeBadProperties($reflect->getProperties(), $columnValues);

        $this->db->builder()->insert($reflect->getProperty("TABLE_NAME")->getValue(), $columnValues)->exec();
    }

    /**
     * @param Entity $entity
     * @return void
     * @throws ORMException
     */
    public function push(Entity $entity): void
    {
        $this->db->builder()->insert($entity::$TABLE_NAME, $entity->toAssocArray())->exec();
    }

    /**
     * @throws ReflectionException
     * @throws ORMException
     */
    public function delete(string $entityName, array $columnValues = [], mixed ...$kwargs): void
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->_getEntityReflect($entityName);
        $this->_removeBadProperties($reflect->getProperties(), $columnValues);

        $this->db->builder()->delete( $reflect->getProperty("TABLE_NAME")->getValue() )->where($columnValues)->exec();
    }
}