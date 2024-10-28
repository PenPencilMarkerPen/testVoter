<?php

namespace App\Serializer;

use ApiPlatform\State\SerializerContextBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use App\Entity\Product;

#[AsDecorator('api_platform.serializer.context_builder')]
class ProductContextBuilder implements SerializerContextBuilderInterface {

    public function __construct(
        private SerializerContextBuilderInterface $decorated,
        private Security $security,
        private EntityManagerInterface $entityManagerInterface,
    )
    {}

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
       

        if ($context['resource_class'] !== Product::class || !isset($context['uri_variables']) || !isset($context['uri_variables']['id']) )
        {
            return $context;
        }

        $id= $context['uri_variables']['id'];
        $user = $this->security->getUser();
        $product=$this->getProduct($id);

        if ($product &&  $user===$product->brand->users)
        {
            $context['groups'][] = $normalization ? Product::PRODUCT_READ_OWNER : Product::PRODUCT_WRITE_OWNER;
        }
        return $context;
    }

    private function getProduct($id):Product|null
    {
        $product = $this->entityManagerInterface->getRepository(Product::class)->find($id);
        
        return $product;
    }
}