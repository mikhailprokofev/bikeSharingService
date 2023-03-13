<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends DefaultController
{
    public function __construct(
        private readonly \Doctrine\Persistence\ManagerRegistry $registry,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly TokenStorageInterface $tokenStorage,
    ) {}

    #[
        Route(
            '/api/user',
            name:'user_add',
            methods:['POST'],
        )
    ]
    public function create(Request $request): JsonResponse
    {
        $result = json_decode($request->getContent());
        $entityManager = $this->registry->getManager();
        $uuid = Uuid::v4();
        $user = new User(
            $uuid,
            $result->login,
            $result->username,
            "0123456789",
        );

        $user->setPassword($this->passwordHasher->hashPassword($user, $result->password));

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json($user, 200);
    }

    #[
        Route(
            '/api/user',
            name:'user_edit',
            methods:['PUT'],
        )
    ]
    public function edit(Request $request): JsonResponse
    {
        $result = json_decode($request->getContent());
        if (!empty($result->id)){
            $entityManager = $this->registry->getManager();
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['id' => $result->id]);

            $user->setUsername($result->username ?? $user->getUsername());

            if (!empty($result->password))
                $user->setPassword($this->passwordHasher->hashPassword($user, $result->password));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json($user, 200);
        }
        
        return $this->json(['Error'=>"Логин отсутствует"], 500);
    }

    #[
        Route(
            '/api/user/{id}',
            name:'user_view',
            methods:['GET'],
        )
    ]
    public function view(string $id): JsonResponse
    {
        if (!empty($id)){
            $entityManager = $this->registry->getManager();
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['id' => $id]);
            return $this->json($user, 200);
        }
        return $this->json(['Error'=>"Логин отсутствует"], 500);
    }

    #[
        Route(
            '/api/user/{id}',
            name:'user_remove',
            methods:['DELETE'],
        )
    ]
    public function delete(string $id): JsonResponse
    {
        if (!empty($id)){
            $entityManager = $this->registry->getManager();
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['id' => $id]);
                $entityManager->remove($user);
                $entityManager->flush();
            return $this->json(['Status'=> "Пользователь успешно удален"], 200);
        }
        return $this->json(['Error'=>"Логин отсутствует"], 500);
    }

    #[
        Route(
            '/api/users/all',
            name:'user_all',
            methods:['GET'],
        )
    ]
    public function allUsers(): JsonResponse
    {
        $entityManager = $this->registry->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->json($users, 200);
    }

    // #[
    //     Route(
    //         '/api/user/current',
    //         name:'user_current',
    //         methods:['GET'],
    //     )
    // ]
    // public function currentUsers(): JsonResponse
    // {
    //     $user = $this->tokenStorage->getToken()->getUser();
    //     return $this->json($user, 200);
    // }
}