<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Controller\Auth;

use Symfony\Component\HttpFoundation\JsonResponse;

final class SignUpAction
{
    public function __invoke(): JsonResponse
    {
        $response = new JsonResponse(['fd' => 'tst']);
        return $response;
    }
}
