<?php

namespace App\Serializer;

use ApiPlatform\State\SerializerContextBuilderInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class ProductContextBuilder implements SerializerContextBuilderInterface {

    public function __construct(
        private SerializerContextBuilderInterface $decorated,
        private Security $security,
    )
    {
        
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        // dump( $context);
        return [];
    }
}