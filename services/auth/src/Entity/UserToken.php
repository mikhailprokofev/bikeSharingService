<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
    ORM\Table(name: 'user_tokens')
]
class UserToken
{
    #[
        ORM\Id,
        ORM\Column(type: 'uuid')
    ]
    private Uuid $id;
    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;
    #[ORM\Column(type: 'string', length: 32, unique:true)]
    private string $token;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $expiredAt;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    private static function create(User $user, string $token, int $expire = 3650): self
    {
        $model = new self();
        $model->id = Uuid::v4();
        $model->user = $user;
        $model->token = $token;
        $model->expiredAt = new DateTimeImmutable("+{$expire} second");
        $model->createdAt = new DateTimeImmutable();
        return $model;
    }

    public static function refreshToken(User $user, int $expire = 86400): self
    {
        $token = \hash('md5', \random_bytes(32));
        return self::create($user, $token, $expire);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function expired(): void
    {
        $this->expiredAt = new DateTimeImmutable();
    }

    public function isExpired(): bool
    {
        return $this->expiredAt->diff(new DateTimeImmutable())->invert === 0;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
