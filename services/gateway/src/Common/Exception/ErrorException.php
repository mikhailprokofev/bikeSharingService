<?php

declare(strict_types=1);

namespace Gateway\Common\Exception;

use Exception;

class ErrorException extends Exception
{
    private const PUBLIC_MESSAGE = 'Failed request';

    private array $data;

    public function __construct(
        Exception $exception,
        ?string $message = null,
    ) {
        $this->data = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ];

        $message = $exception instanceof \DomainException ? $exception->getMessage() : $message ?? self::PUBLIC_MESSAGE;
        parent::__construct($message);
    }

    public function __toString(): string
    {
        return implode(' :: ', $this->data);
    }
}
