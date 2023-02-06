<?php

declare(strict_types=1);

namespace Gateway\Common\Exception;

use Exception;

final class ValidationException extends Exception
{
    private const PUBLIC_MESSAGE = 'Invalid data';

    private array $data;

    public function __construct(
        array $errors,
    ) {
        $this->data = array_map(fn (array $error) => [
            'field' => $error['path'],
            'message' => $error['message'],
        ], $errors);

        parent::__construct(self::PUBLIC_MESSAGE);
    }

    public function __toString(): string
    {
        return implode(' :: ', $this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
