<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;  
use App\Entity\File;

class FileVoter extends Voter {

    public function __construct(
        private Security $security,
    )
    {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['FILE_CREATE', 'FILE_DELETE']);
        $supportsSubject = $subject instanceof File;
        return $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface)
            return false;

        if ($this->security->isGranted('ROLE_USER'))
        {
            return $user===$subject->getProduct()->getBrand()->getUsers();
        }

        return false;
    }


}