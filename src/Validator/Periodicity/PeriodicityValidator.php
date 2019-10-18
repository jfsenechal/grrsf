<?php

namespace App\Validator\Periodicity;

use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodicityValidator extends ConstraintValidator
{
    /**
     * @param \App\Entity\Periodicity                $value
     * @param \App\Validator\Periodicity\Periodicity $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof \App\Entity\Periodicity) {
            throw new \UnexpectedValueException($value, 'Periodicity');
        }

        $typePeriodicity = $value->getType();

        if (null === $typePeriodicity || 0 === $typePeriodicity) {
            return;
        }

        $endPeriodicity = Carbon::instance($value->getEndTime());
        $entry = $value->getEntryReference();
        $entryEndTime = Carbon::instance($entry->getEndTime());

        /*
         * La date de fin de la periodicité doit être plus grande que la date de fin de la réservation
         */
        if ($endPeriodicity->format('Y-m-d') <= $entryEndTime->format('Y-m-d')) {
            $this->context->buildViolation('periodicity.constraint.end_time')
                ->addViolation();

            return;
        }
    }
}
