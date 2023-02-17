<?php

namespace FastFramework\ORM\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Column
{
    private readonly array $options;

    public function __construct(private readonly ?string $columnName = null, mixed ...$options) { $this->options = $options; }

    public function __get(string $name): mixed
    {
        return (isset($this->options[$name]) && !empty($this->options[$name])) ? $this->options[$name] : null;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string|null
     */
    public function getColumnName(): ?string
    {
        return $this->columnName;
    }



}