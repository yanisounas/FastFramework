<?php

namespace App\Entity;

use FastFramework\AbstractClass\Entity;
use FastFramework\ORM\Attributes\Column;

class UsersEntity extends Entity
{
    #[Column("varchar", 50)]
    protected ?string $username;

    #[Column("varchar", 50)]
    protected ?string $password;

}