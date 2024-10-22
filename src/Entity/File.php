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
    normalizationContext: ['groups' => ['file:read']],
    denormalizationContext: ['groups' => ['file:write']],
)]
#[Post(
    outputFormats: ['jsonld' => ['application/ld+json']],
    inputFormats: ['multipart' => ['multipart/form-data']],
    securityPostDenormalize: "is_granted('FILE_CREATE', object)"
)]
#[GetCollection()]
#[Delete(security: "is_granted('FILE_DELETE', object)")]
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;

    #[Groups(['file:read'])]
    #[ORM\Column()]
    public ?string $mimeType = null;

    #[Groups(['file:write'])]
    #[Vich\UploadableField(mapping: 'files', fileNameProperty: 'filePath', mimeType: 'mimeType', size: 'fileSize')]
    public FileApi $file;

    #[ORM\Column()] 
    #[Groups(['product:read','file:read'])]
    public ?string $filePath = null;

    #[ORM\Column()] 
    #[Groups(['file:read'])]
    public ?string $fileSize = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[Groups(['file:read', 'file:write'])]
    private ?Product $product = null;

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
