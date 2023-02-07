<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Messager\Message\Auth;

final class RefreshTokenMessage
{
    public function __construct(
        private string $token,
    ) {}

    public function getToken(): string
    {
        return $this->token;
    }
}
