<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Controller\Auth;

use Exception;
use Gateway\Infrastructure\Collector\Auth\SignUpCollector;
use Gateway\Infrastructure\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class SignUpAction extends AbstractController
{
    public function __construct(
        private SignUpCollector $collector,
        private LoggerInterface $logger,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->validate($request, $this->collector);
            $response = new JsonResponse(['fd' => 'tst']);
            return $response;
        } catch (Exception $e) {
            // TODO: пока так, потом события
            return $this->reactOnError($this->logger, $e);
        }
    }
}
