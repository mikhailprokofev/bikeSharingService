<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Controller\Auth;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class RefreshTokenAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $response = new JsonResponse(['fd' => 'tst']);
        return $response;
    }
}
