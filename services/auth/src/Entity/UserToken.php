<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

class UserToken
{
    private int $id;
    private User $user;
    private string $token;
    private DateTimeImmutable $expiredAt;
    private DateTimeImmutable $createdAt;

    private static function create(User $user, string $token, int $expire = 3650): self
    {
        $model = new self();
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

    public function isExpired(): bool
    {
        return $this->expiredAt->diff(new DateTimeImmutable())->invert === 0;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
