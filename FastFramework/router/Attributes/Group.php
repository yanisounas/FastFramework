<?php

namespace FastFramework\Router\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Group
{
    public function __construct(private ?string $groupName = null) {}

    public function getGroupName(): ?string {return $this->groupName;}
}