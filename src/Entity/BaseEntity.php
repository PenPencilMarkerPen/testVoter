<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class BaseEntity {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;
    
}