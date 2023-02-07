<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Messager\Message\Auth;

final class AcceptRefreshTokenMessage
{
    public function __construct(
        private string $result,
        private string $hello,
        private string $hello2,
    ) {}

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getHello(): string
    {
        return $this->hello;
    }

    /**
     * @return string
     */
    public function getHello2(): string
    {
        return $this->hello2;
    }
}
