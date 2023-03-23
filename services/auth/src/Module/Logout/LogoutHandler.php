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
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create(
                $this->em->getConnection(),
                $this->em->getConfiguration()
            );
        }

        $this->expiredToken($refreshToken);
        $this->em->flush();
        $this->em->clear();
        return [
            'message' => "You logged out",
        ];
    }

    private function expiredToken(string $refreshToken): void
    {

        if (!$token = $this->em->getRepository(UserToken::class)->findOneBy(['token' => $refreshToken])) {
            throw new DomainException('Token not exist');
        }

        if ($token->isExpired()) {
            throw new DomainException('Token is expired');
        }

        $token->expired();
        $this->em->persist($token);
    }
}