<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Collector\Auth;

use Gateway\Infrastructure\Collector\AbstractCollector;
use Gateway\Module\Auth\RefreshToken\RefreshTokenInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Composite;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RefreshTokenCollector extends AbstractCollector
{
    public function __construct(
        ValidatorInterface $validator,
    ) {
        $this->validator = $validator;
    }

    public function collect(Request $request): RefreshTokenInput
    {
        // TODO: поправить получение параметров

        return new RefreshTokenInput(
            $request->toArray()['token'],
        );
    }

    protected function constraints(): Composite
    {
        return new Collection([
            'token' => [
                new NotBlank(),
            ],
        ]);
    }
}
