<?php

declare(strict_types=1);

namespace App\Module\Register;

use DomainException;
use App\Entity\User;
use App\Entity\UserToken;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterHandler
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly JWTTokenManagerInterface $tokenManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function prepare(array $data)
    {
        $this->validate($data);
        $user = $this->create($data);
        $request = $this->login($user);
        $this->em->flush();
        return $request;
    }

    public function validate(array $data)
    {
        if (empty($data['password']) || $data['password'] == '') {
            throw new DomainException('Empty password');
        }

        if (empty($data['login']) || $data['login'] == '') {
            throw new DomainException('Empty login');
        }

        if (empty($data['username']) || $data['username'] == '') {
            throw new DomainException('Empty username');
        }
    }

    public function create(array $data): User
    {
        $user = new User(
            Uuid::v4(),
            $data['login'],
            $data['username'],
            "0123456789",
        );

        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $this->em->persist($user);
        return $user;
    }

    public function login($user)
    {
        $token = $this->tokenManager->create($user);
        $refreshToken = UserToken::refreshToken($user, 86400);
        $this->em->persist($refreshToken);
        return [
            'access_token'  => $token,
            'refresh_token' => $refreshToken->getToken(),
            'user'          => $user,
        ];
    }
}