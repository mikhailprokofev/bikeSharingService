<?php

declare(strict_types=1);

namespace App\Controller;

use App\Module\Login\LoginHandler;
use App\Module\Logout\LogoutHandler;
use App\Module\Refresh\RefreshHandler;
use App\Module\Register\RegisterHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthController extends DefaultController
{
    public function __construct(
        private readonly \Doctrine\Persistence\ManagerRegistry $registry,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly TokenStorageInterface $tokenStorage,
        private LoginHandler $loginHandler,
        private LogoutHandler $logoutHandler,
        private RefreshHandler $refreshHandler,
        private RegisterHandler $registerHandler,
    ) {}

    #[
        Route(
            '/api/auth/signin',
            name:'auth_login',
            methods:['POST'],
        )
    ]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        
        return $this->json($this->loginHandler->prepare($data->login,$data->password));
    }

    #[
        Route(
            '/api/auth/register',
            name:'auth_register',
            methods:['POST'],
        )
    ]
    public function register(Request $request): JsonResponse
    {
        $data = (array) json_decode($request->getContent());

        return $this->json($this->registerHandler->prepare($data));
    }

    #[
        Route(
            '/api/auth/refresh',
            name:'auth_refresh',
            methods:['POST'],
        )
    ]
    public function refresh(Request $request): JsonResponse
    {
        $data = (array) json_decode($request->getContent());
        return $this->json($this->refreshHandler->prepare($data['refresh_token']));;
    }

    #[
        Route(
            '/api/auth/signout',
            name:'auth_logout',
            methods:['POST'],
        )
    ]
    public function logout(Request $request): JsonResponse
    {
        $data = (array) json_decode($request->getContent());
        return $this->json($this->logoutHandler->prepare($data['refresh_token']));;    }
}