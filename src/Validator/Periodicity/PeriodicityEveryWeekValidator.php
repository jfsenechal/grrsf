<?php

namespace App\Validator\Periodicity;

use App\Periodicity\PeriodicityConstant;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodicityEveryWeekValidator extends ConstraintValidator
{
    /**
     * @param \App\Entity\Periodicity $value
     * @param PeriodicityEveryWeek    $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof \App\Entity\Periodicity) {
            throw new \UnexpectedValueException($value, 'Periodicity');
        }

        if (PeriodicityConstant::EVERY_WEEK !== $value->getType()) {
            return;
        }

        $endPeriodicity = Carbon::instance($value->getEndTime());
        $entry = $value->getEntryReference();
        $entryEndTime = Carbon::instance($entry->getEndTime());
        $daysSelected = $value->getWeekDays();
        $weekRepeat = $value->getWeekRepeat();
        $entryStartTime = Carbon::instance($entry->getStartTime());

        /*
         * Si aucun jour de la semaine sélectionné
         */
        if (count($daysSelected) < 1) {
            $this->context->buildViolation('periodicity.constraint.every_weeks.no_day')
                ->addViolation();
        }

        /*
         * Si aucune répétion par semaine choisie
         */
        if (null === $weekRepeat) {
            $this->context->buildViolation('periodicity.constraint.every_weeks.no_repeat')
                ->addViolation();
        }

        /**
         * En répétition par semain, il y doit y avoir au moins $weekRepeat semaine de différence entre la fin de la périodicité
         * et la fin de la réservation.
         */
        if ($entryEndTime->diffInWeeks($endPeriodicity) < $weekRepeat) {
            $this->context->buildViolation('periodicity.constraint.every_weeks.endtime')
                ->addViolation();

            return;
        }

        /*
         * En répétion par semaine, la réservation ne peut s'étaler sur plusieurs jours
         */
        if ($entryStartTime->diffInDays($entryEndTime) > 1) {
            $this->context->buildViolation('periodicity.constraint.every_weeks.more24h')
                ->addViolation();
        }
    }
}
