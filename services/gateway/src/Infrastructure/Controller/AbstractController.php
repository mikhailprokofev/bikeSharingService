<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Controller;

use Exception;
use Gateway\Common\Exception\ErrorException;
use Gateway\Common\Exception\ValidationException;
use Gateway\Infrastructure\Collector\AbstractCollector;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

// TODO: это должен быть не абстрактный класс а что-то другое
// TODO: возможно изначально трейт
abstract class AbstractController extends SymfonyAbstractController
{
    /**
     * @param Request $request
     * @param AbstractCollector $collector
     * @return void
     * @throws Exception
     */
    protected function validate(Request $request, AbstractCollector $collector): void
    {
        $collector->validate($request);
    }

    protected function collectData(Request $request, AbstractCollector $collector): mixed
    {
        return $collector->collect($request);
    }

    protected function reactOnError(LoggerInterface $logger, Exception $e): JsonResponse
    {
        $logger->error(new ErrorException($e));

        $response = [
            'message' => $e->getMessage(),
        ];

        if ($e instanceof ValidationException) {
            $response = array_merge($response, ['errors' => $e->getData()]);
        }

        return new JsonResponse($response);
    }

    protected function makeResponse(mixed $output): JsonResponse
    {
        return new JsonResponse($output);
    }
}
