<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class BaseEntity {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $date=null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->date = new \DateTimeImmutable();
    }
}