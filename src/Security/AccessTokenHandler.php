<?php

namespace App\Security;

use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface {
    
    public function __construct(
        private TokenRepository $tokenRepository
    )
    {      
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $token = $this->tokenRepository->findOneBy(['token' => $accessToken]);

        $user = $token->getUsers();

        if ($user === null)
            throw new BadCredentialsException('Invalid credentials');
        
        return new UserBadge($user->email);
    }
}