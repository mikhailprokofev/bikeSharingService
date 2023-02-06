<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Collector\Auth;

use Gateway\Infrastructure\Collector\AbstractCollector;
use SignUpInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Composite;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SignUpCollector extends AbstractCollector
{
    public function __construct(
        ValidatorInterface $validator,
    ) {
        $this->validator = $validator;
    }

    public function collect(Request $request): SignUpInput
    {
        // TODO: поправить получение параметров

        return new SignUpInput(
            $request->toArray()['email'],
            $request->toArray()['fullname'],
            $request->toArray()['password'],
        );
    }

    protected function constraints(): Composite
    {
        return new Collection([
            'email' => [
                new NotBlank(),
                new Email(),
            ],
            'fullname' => [
                new NotBlank(),
            ],
            'password' => [
                new NotBlank(),
                new NotCompromisedPassword(),
                new Length(min: 6),
                new Regex('/^\w+$/'),
            ],
        ]);
    }
}
