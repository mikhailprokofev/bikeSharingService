<?php

declare(strict_types=1);

namespace App\Module\Login;

use DomainException;
use App\Entity\User;
use App\Entity\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class LoginHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly JWTTokenManagerInterface $tokenManager,
    ){}

    public function prepare(string $login, string $password)
    {
        $user = $this->findUser($login);
        $this->validatePassword($password, $user);
        $output = $this->makeResponse($user);
        $this->em->flush();
        return $output;
    }

    private function findUser(string $login): User
    {

        if (!$user = $this->em->getRepository(User::class)->findOneBy(['login' => $login])) {
            throw new DomainException('User not exist');
        }

        return $user;
    }

    private function validatePassword(string $password, User $user)
    {
        if (!$this->userPasswordHasher->isPasswordValid($user,$password)) {
            throw new DomainException('Incorrect password');
        }
    }

    private function makeResponse(User $user): array
    {
        $token = $this->tokenManager->create($user);
        $refreshToken = UserToken::refreshToken($user, 86400);
        return [
            'access_token'  => $token,
            'refresh_token' => $refreshToken->getToken(),
            'user'          => $user,
        ];
    }
}