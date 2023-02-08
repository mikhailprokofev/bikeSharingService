<?php

declare(strict_types=1);

namespace Gateway\Component\Serializer\Exception;

use Exception;

final class ExternalMessageDecodingException extends Exception
{
    private const PUBLIC_MESSAGE = 'Error during decode external message';

    public function __construct(
        string $message = '',
    ) {
        parent::__construct($message ?? self::PUBLIC_MESSAGE);
    }
}
