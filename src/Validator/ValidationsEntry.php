<?php

namespace App\Validator;

use App\Entity\Entry;
use App\Validator\Entry\BusyRoom;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationsEntry
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Entry $entry
     *
     * @return ConstraintViolationList[]
     */
    public function validate(Entry $entry): array
    {
        $violations = [];
        $validators = $this->getValidators();
        foreach ($validators as $validator) {
            $constraint = new $validator();
            $violations[] = $this->validator->validate($entry, $constraint);
        }

        return $violations;
    }

    /**
     * @return string[]
     */
    protected function getValidators(): array
    {
        return [BusyRoom::class];
    }
}
