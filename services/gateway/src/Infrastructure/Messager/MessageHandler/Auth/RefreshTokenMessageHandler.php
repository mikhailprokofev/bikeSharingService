<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Messager\MessageHandler\Auth;

use Gateway\Infrastructure\Messager\Message\Auth\RefreshTokenMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RefreshTokenMessageHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(RefreshTokenMessage $message): void
    {
        $this->logger->debug('world');
    }
}
