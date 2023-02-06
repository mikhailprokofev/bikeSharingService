<?php

declare(strict_types=1);

namespace Gateway\Infrastructure\Collector;

use Gateway\Common\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Composite;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractCollector
{
    protected ValidatorInterface $validator;

    abstract public function collect(Request $request): mixed;

    abstract protected function constraints(): Composite;

    /**
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    public function validate(Request $request): void
    {
        $errors = $this->validator->validate($this->getContent($request), $this->constraints());

        if ($errors->count()) {
            // TODO: Подумать над реализацией вывода в эксепшн/лог (события может быть)
            throw new ValidationException($this->prepareErrorBags($errors));
        }
    }

    private function getContent(Request $request): array
    {
        // TODO: разобраться с реквестом
        return array_merge(
            $request->query->all(),
            $request->toArray(),
            $request->files->all()
        );
    }

    // TODO: подумать над структурой вывода
    protected function prepareErrorBags(ConstraintViolationListInterface $violations): array
    {
        $bags = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $bag['path'] = $this->preparePropertyName($violation->getPropertyPath());
            $bag['message'] = $violation->getMessage();

            $bags[] = $bag;
        }

        return $bags;
    }

    protected function preparePropertyName(string $property): string
    {
        return str_replace(['[', ']'], ['', ''], $property);
    }

    protected function concatBags()
    {

    }
}
