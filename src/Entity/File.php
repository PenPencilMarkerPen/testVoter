<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\HttpFoundation\File\File as FileApi;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Metadata\Delete;


#[ApiResource(
    normalizationContext: ['groups' => [self::FILE_READ]],
    denormalizationContext: ['groups' => [self::FILE_WRITE]],
)]
#[Post(
    outputFormats: ['jsonld' => ['application/ld+json']],
    inputFormats: ['multipart' => ['multipart/form-data']],
    securityPostDenormalize: "is_granted('FILE_CREATE', object)"
)]
#[GetCollection()]
#[Delete(security: "is_granted('FILE_DELETE', object)")]
#[Vich\Uploadable]
#[ORM\Entity()]
class File extends BaseEntity
{
    public const FILE_READ='file:read';
    public const FILE_WRITE='file:write';

    #[Groups([self::FILE_READ])]
    #[ORM\Column()]
    public ?string $mimeType = null;

    #[Groups([self::FILE_WRITE])]  
    #[Vich\UploadableField(mapping: 'files', fileNameProperty: 'filePath', mimeType: 'mimeType', size: 'fileSize')]
    public FileApi $file;

    #[ORM\Column()] 
    #[Groups([Product::PRODUCT_READ, self::FILE_READ])]
    public ?string $filePath = null;

    #[ORM\Column()] 
    #[Groups([self::FILE_READ])]
    public ?string $fileSize = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[Groups([self::FILE_WRITE,self::FILE_READ])]
    public ?Product $product = null;

}
