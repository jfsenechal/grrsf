<?php

namespace App\Validator\Periodicity;

use App\Entity\Periodicity;
use InvalidArgumentException;
use App\Periodicity\PeriodicityConstant;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodicityEveryMonthValidator extends ConstraintValidator
{
    /**
     * @param \App\Entity\Periodicity|null $value
     * @param PeriodicityEveryWeek         $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value) {
            return;
        }

        if (!$value instanceof Periodicity) {
            throw new InvalidArgumentException($value, 0);
        }

        $typePeriodicity = $value->getType();

        if (PeriodicityConstant::EVERY_MONTH_SAME_DAY !== $typePeriodicity && PeriodicityConstant::EVERY_MONTH_SAME_WEEK_DAY !== $typePeriodicity) {
            return;
        }

        $endPeriodicity = Carbon::instance($value->getEndTime());
        $entry = $value->getEntryReference();
        $entryEndTime = Carbon::instance($entry->getEndTime());

        /*
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
