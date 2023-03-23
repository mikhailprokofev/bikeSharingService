<?php

declare(strict_types=1);

namespace App\Module\Refresh;

use DomainException;
use App\Entity\User;
use App\Entity\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RefreshHandler
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

        $token  = $this->expiredToken($refreshToken);
        $user   = $token->getUser();
        $output = $this->makeResponse($user);
        $this -> em -> flush();
        $this->em->clear();
        return $output;
    }

    private function expiredToken(string $refreshToken): UserToken
    {

        if (!$token = $this->em->getRepository(UserToken::class)->findOneBy(['token' => $refreshToken])) {
            throw new DomainException('Token not exist');
        }

        if ($token->isExpired()) {
            throw new DomainException('Token is expired');
        }

        $token -> expired();
        $this -> em -> persist($token);
        return $token;
    }

    private function makeResponse(User $user): array
    {
        $token = $this -> tokenManager -> create($user);
        $refreshToken = UserToken::refreshToken($user, 86400);
        $this -> em -> persist($refreshToken);
        return [
            'access_token'  => $token,
            'refresh_token' => $refreshToken -> getToken(),
            'user'          => $user,
        ];
    }
}