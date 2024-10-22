<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Book;

class BookVoter extends Voter {

    public function __construct(
        private Security $security)
    {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['BOOK_READ']);
        $supportsSubject = $subject instanceof Book;
        return $supportsSubject && $supportsAttribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface)
            return false;

        if ('BOOK_READ' === $attribute)
            return $user === $subject->getUsers();

        return false;
    }
}