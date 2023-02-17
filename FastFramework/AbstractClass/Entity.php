<?php
declare(strict_types=1);

namespace FastFramework\AbstractClass;

use FastFramework\ORM\Attributes\Column;

abstract class Entity
{
    public static ?string $TABLE_NAME = null;

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

    public function toAssocArray(): array
    {
        $assocArray = [];
        foreach ((new \ReflectionClass($this))->getProperties() as $property)
            if ($property->getName() != "id" && count($property->getAttributes(Column::class)) > 0)
                $assocArray[$property->getName()] = $property->getValue($this);

        return $assocArray;
    }

    public static function toAssocArrayAll(array &$entities): array
    {
        foreach ($entities as &$entity)
            $entity = $entity->toAssocArray();

        return $entities;
    }

    public function load(array $values): Entity
    {
        foreach ($values as $key => $value)
            $this->__set($key, $value);

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}