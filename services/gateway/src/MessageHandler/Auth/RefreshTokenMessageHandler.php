<?php

declare(strict_types=1);

namespace Gateway\MessageHandler\Auth;

use Gateway\Message\Auth\RefreshTokenMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RefreshTokenMessageHandler
{
    public function __invoke(RefreshTokenMessage $message): void
    {
        // TODO: Implement __invoke() method.
    }
}
