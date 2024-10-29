<?php

namespace App\Normalizer;

use App\Entity\Product;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;


// #[AsDecorator('api_platform.jsonld.normalizer.item')]
class ProductOwnerNormalize  implements NormalizerInterface, SerializerAwareInterface {

    private const ALREADY_CALLED = 'PRODUCT_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';

 
    public function __construct(
        private NormalizerInterface $normalizer,
        private Security $security
    )
    {}

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null {


        if ($object instanceof Product && $this->security->getUser() == $object->brand->users)
        {
            array_push($context['groups'],Product::PRODUCT_READ_OWNER);
        }

        $context[self::ALREADY_CALLED] = true;
    
        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool {
        
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Product;

        // return false;
    }

    public function getSupportedTypes(?string $format): array{

        return $this->normalizer->getSupportedTypes($format);
    }

    public function setSerializer(SerializerInterface $serializer):void
    {
        if($this->normalizer instanceof SerializerAwareInterface) {
            $this->normalizer->setSerializer($serializer);
        }
    }
}