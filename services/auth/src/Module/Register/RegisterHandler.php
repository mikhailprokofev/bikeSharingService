<?php

declare(strict_types=1);

namespace App\Module\Register;

use DomainException;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use App\Module\Login\LoginHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterHandler
{

    public function __construct(
        private readonly \Doctrine\Persistence\ManagerRegistry $registry,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private LoginHandler $loginHandler,
    ) {}

    public function prepare(array $data)
    {
        $this->validate($data);
        $user = $this->create($data);
        return $this->login($user, $data['password']);
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

        $entityManager = $this->registry->getManager();

        $user = new User(
            Uuid::v4(),
            $data['login'],
            $data['username'],
            "0123456789",
        );

        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    public function login($user,$pass)
    {
        $login = $user->login;
        return $this->loginHandler->prepare($login,$pass);
    }
}