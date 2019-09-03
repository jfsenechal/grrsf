<?php

namespace App\Validator\Periodicity;

use App\Periodicity\PeriodicityConstant;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodicityEveryDayValidator extends ConstraintValidator
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

        if ($value->getType() !== PeriodicityConstant::EVERY_DAY) {
            return;
        }

        $endPeriodicity = Carbon::instance($value->getEndTime());
        $entry = $value->getEntry();
        $entryStartTime = Carbon::instance($entry->getStartTime());
        $entryEndTime = Carbon::instance($entry->getEndTime());

        /**
         * En répétion tous les jours, la réservation ne peut s'étaler sur plusieurs jours
         */
        if ($entryStartTime->diffInDays($entryEndTime) > 1) {
            $this->context->buildViolation('periodicity.constraint.every_day')
                ->addViolation();
        }

        /**
         * En répétition par jour, il y doit y avoir au moins un jour de différence entre la fin de la périodicité
         * et la fin de la réservation
         */
        if ($entryEndTime->diffInDays($endPeriodicity) < 1) {
            $this->context->buildViolation('periodicity.constraint.end_time')
                ->addViolation();

            return;
        }

    }
}