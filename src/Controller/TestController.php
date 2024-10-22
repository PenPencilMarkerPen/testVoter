<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class TestController extends AbstractController
{
    public function __construct(
        private Security $security,
    )
    {}


    #[Route('/api/test', name: 'app_api_test')]
    public function index(): JsonResponse
    {
        var_dump($this->security->getUser()->email);

        return $this->json([
            'message' => 'Welcome to your new controller!',
        ]);
    }
}
