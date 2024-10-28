<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;  
use App\Entity\Brand;

class BrandVoter extends Voter{

    public function __construct(
        private Security $security,
    )
    {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['BRAND_READ', 'BRAND_EDIT', 'BRAND_CREATE', 'BRAND_DELETE']);
        $supportsSubject = $subject instanceof Brand;
        return  $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        $user = $token->getUser();

        if (!$user instanceof UserInterface)
            return false;

        if ($this->security->isGranted('ROLE_USER'))
        {
            return $user===$subject->users;
        }
        
        return false;
    }
}