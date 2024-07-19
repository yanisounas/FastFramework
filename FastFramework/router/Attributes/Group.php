<?php
declare(strict_types=1);

namespace FastFramework\Router\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Group
{
    public function __construct(private ?string $groupName = null) {}

    /*
     * @param string $targetName
     * @return string The group name. If a group name has already been declared in the target class: returns it, otherwise returns the class name without the "Controller" suffix
     */
    public function getGroupName(string $targetName): string
    {
        return $this->groupName ?? explode("Controller", array_slice(explode("\\", $targetName), -1)[0])[0];
    }
}