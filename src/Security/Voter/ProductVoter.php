<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;  
use App\Entity\Product;

class ProductVoter extends Voter {

    public function __construct(
        private Security $security
    )
    {}

    protected function supports(string $attribute, mixed $subject): bool
    {   
        $supportsAttribute = in_array($attribute, ['PRODUCT_READ', 'PRODUCT_EDIT', 'PRODUCT_CREATE', 'PRODUCT_DELETE']);
        $supportsSubject = $subject instanceof Product;
        return $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface)
            return false;

        if ($this->security->isGranted('ROLE_USER'))
        {
            return $user===$subject->brand->users;
        }
        return true;
    }
}