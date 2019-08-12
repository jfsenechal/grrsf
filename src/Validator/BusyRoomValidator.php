<?php

namespace App\Validator;

use App\Entity\Entry;
use App\Repository\EntryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BusyRoomValidator extends ConstraintValidator
{
    /**
     * @var EntryRepository
     */
    private $entryRepository;

    public function __construct(EntryRepository $entryRepository)
    {
        $this->entryRepository = $entryRepository;
    }

    /**
     * @param Entry    $value
     * @param BusyRoom $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Entry) {
            throw new \UnexpectedValueException($value, 'Entry');
        }

        dump($value);
        $room = $value->getRoom();

        $entries = $this->entryRepository->isBusy($value, $room);

        if (count($entries) > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
