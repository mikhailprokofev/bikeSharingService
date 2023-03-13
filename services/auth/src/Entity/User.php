<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[
    ORM\Entity,
    ORM\Table(name: 'users')
]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \JsonSerializable
{
    public function __construct(
        #[
            ORM\Id,
            ORM\Column(type: 'uuid')
        ]
        private readonly Uuid $id,
        #[ORM\Column(type: 'string', length: 25, unique:true)]
        private readonly string $login,
        #[ORM\Column(type: 'string', length: 255)]
        private string $username,
        #[ORM\Column(type: 'string', length: 60)]
        private string $password,
        #[ORM\Column(type: 'json')]
        private array $roles = [],
    ) {}

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id'            => $this->getId(),
            'username'           => $this->getUsername(),
            'login'         => $this->getLogin(),
        ];
    }
}