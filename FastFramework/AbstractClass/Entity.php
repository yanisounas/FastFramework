<?php
declare(strict_types=1);

namespace FastFramework\AbstractClass;

use FastFramework\ORM\Attributes\Column;
use ReflectionProperty;

abstract class Entity
{
    public static ?string $TABLE_NAME = null;
    protected ?array $columns = null;

    #[Column("int", 11, "AUTO_INCREMENT, PRIMARY KEY")]
    protected int $id;

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    /**
     * @param bool $withId
     * @return array
     */
    public function toAssocArray(bool $withId = true): array { return $this->getColumns($withId); }

    /**
     * @param array $entities
     * @param bool $withId
     * @return array
     */
    public static function toAssocArrayAll(array &$entities, bool $withId = true): array
    {
        foreach ($entities as &$entity)
            $entity = $entity->toAssocArray($withId);

        return $entities;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function load(array $values): Entity
    {
        foreach ($values as $key => $value)
            $this->__set($key, $value);

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int { return $this->id; }

    /**
     * @param ReflectionProperty $property
     * @return bool
     */
    public function isColumn(ReflectionProperty $property): bool { return count($property->getAttributes(Column::class)) > 0; }

    /**
     * @param bool $withId
     * @return array
     */
    public function getColumns(bool $withId = true): array
    {
        if ($this->columns === null)
        {
            foreach ((new \ReflectionClass($this))->getProperties() as $property)
            {
                if ($property->getName() == "id" && !$withId)
                    break;

                if ($this->isColumn($property))
                {
                    $this->columns[$property->getName()] = $property->getValue($this);
                }
            }

        }

        return $this->columns;
    }
}