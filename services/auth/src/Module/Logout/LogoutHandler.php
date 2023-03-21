<?php

declare(strict_types=1);

namespace App\Module\Logout;

use DomainException;
use App\Entity\User;
use App\Entity\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class LogoutHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly JWTTokenManagerInterface $tokenManager,
    ){}

    public function prepare(string $refreshToken)
    {
        $login = $this->findUser($refreshToken)->getLogin();
        $this->em->flush();
        return [
            'message' => "$login logged out",
        ];
    }

    private function findUser(string $refreshToken): User
    {

        if (!$token = $this->em->getRepository(UserToken::class)->findOneBy(['token' => $refreshToken])) {
            throw new DomainException('Token not exist');
        }

        if ($token->isExpired()) {
            throw new DomainException('Token is expired');
        }

        $token->expired();
        $this->em->persist($token);

        return $token->getUser();
    }
}