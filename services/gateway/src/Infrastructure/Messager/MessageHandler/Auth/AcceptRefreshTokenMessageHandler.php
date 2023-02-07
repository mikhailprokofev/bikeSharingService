<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Messager\MessageHandler\Auth;

use Gateway\Infrastructure\Messager\Message\Auth\AcceptRefreshTokenMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AcceptRefreshTokenMessageHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(AcceptRefreshTokenMessage $message): void
    {
        $this->logger->error('defsfdsf');
    }
}
