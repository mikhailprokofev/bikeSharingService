<?php

declare(strict_types=1);

namespace Gateway\Module\Auth\SignUp;

final class SignUpInput
{
    public function __construct(
        private string $email,
        private string $fullName,
        private string $password,
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }
}
