<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Controller\HealthCheck;

use Symfony\Component\HttpFoundation\JsonResponse;

final class HealthCheckGatewayAction
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse('OK');
    }
}
