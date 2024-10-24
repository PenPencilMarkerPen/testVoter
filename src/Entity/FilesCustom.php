<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]

class FilesCustom extends BaseEntity{
    
    #[ORM\Column(type: 'string')]
    public string $filename;

    #[ORM\Column(type: 'string')]
    public string $mimeType;

    #[ORM\Column(type: 'string')]
    public string $size;

}