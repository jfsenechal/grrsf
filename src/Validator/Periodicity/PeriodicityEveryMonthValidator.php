<?php

namespace App\Validator\Periodicity;

use App\Periodicity\PeriodicityConstant;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodicityEveryMonthValidator extends ConstraintValidator
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

        $typePeriodicity = $value->getType();

        if ($typePeriodicity !== PeriodicityConstant::EVERY_MONTH_SAME_DAY && $typePeriodicity !== PeriodicityConstant::EVERY_MONTH_SAME_WEEK_DAY) {
            return;
        }

        $endPeriodicity = Carbon::instance($value->getEndTime());
        $entry = $value->getEntry();
        $entryEndTime = Carbon::instance($entry->getEndTime());

        /**
         * En répétition par mois, il y doit y avoir au moins un mois de différence entre la fin de la périodicité
         * et la fin de la réservation
         */
        if ($entryEndTime->diffInMonths($endPeriodicity) < 1) {
            $this->context->buildViolation('periodicity.constraint.every_month')
                ->addViolation();

            return;
        }
    }
}
