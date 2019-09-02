<?php

namespace App\Validator\Periodicity;

use App\Periodicity\PeriodicityConstant;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodicityEveryYearValidator extends ConstraintValidator
{
    /**
     * @param \App\Entity\Periodicity $value
     * @param PeriodicityEveryWeek $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof \App\Entity\Periodicity) {
            throw new \UnexpectedValueException($value, 'Periodicity');
        }

        if ($value->getType() !== PeriodicityConstant::EVERY_YEAR) {
            return;
        }

        $endPeriodicity = Carbon::instance($value->getEndTime());
        $entry = $value->getEntry();
        $entryEndTime = Carbon::instance($entry->getEndTime());

        /**
         * En répétition par année, il y doit y avoir au moins un an de différence entre la fin de la périodicité
         * et la fin de la réservation
         */
        if ($entryEndTime->diffInYears($endPeriodicity) < 1) {
            $this->context->buildViolation('periodicity.constraint.every_year')
                ->addViolation();

            return;
        }
    }
}
