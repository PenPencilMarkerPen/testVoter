<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        
    }

    #[Route('/api/token/login', name: 'api_token_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user)
            return $this->json([
            'message' => 'missing credentials',  
        ], Response::HTTP_UNAUTHORIZED);

        $tokenHex = bin2hex(random_bytes(60));

        $token = new Token();
        $token->setToken($tokenHex);
        $user->addToken($token);

        $this->entityManager->persist($user);
        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $tokenHex,
        ]);
    }
}
