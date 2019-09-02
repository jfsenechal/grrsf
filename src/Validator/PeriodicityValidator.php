<?php

namespace App\Validator;

use App\Periodicity\PeriodicityConstant;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodicityValidator extends ConstraintValidator
{
    /**
     * @param \App\Entity\Periodicity $value
     * @param \App\Validator\Periodicity $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $typePeriodicity = $value->getType();

        if (null === $typePeriodicity) {
            return;
        }

        if (!$value instanceof \App\Entity\Periodicity) {
            throw new \UnexpectedValueException($value, 'Periodicity');
        }

        $endPeriodicity = Carbon::instance($value->getEndTime());
        $entry = $value->getEntry();
        $entryStartTime = Carbon::instance($entry->getStartTime());
        $entryEndTime = Carbon::instance($entry->getEndTime());

     //   var_dump($typePeriodicity, $endPeriodicity->toDateString(), $entryEndTime->toDateString());

        /**
         * La date de fin de la periodicité doit être plus grande que la date de fin de la réservation
         */
        if ($endPeriodicity->format('Y-m-d') <= $entryEndTime->format('Y-m-d')) {
            $this->context->buildViolation('periodicity.constraint.end_time')
                ->addViolation();

            return;
        }

        /**
         * En répétion tous les jours, la réservation ne peut s'étaler sur plusieurs jours
         */
        if ($value->getType() === PeriodicityConstant::EVERY_DAY) {
            if ($entryStartTime->format('d') !== $entryEndTime->format('d')) {
                $this->context->buildViolation('periodicity.constraint.every_day')
                    ->addViolation();
            }

            return;
        }

        /**
         * En répétition par mois, il y doit y avoir au moins un mois de différence entre la fin de la périodicité
         * et la fin de la réservation
         */
        if ($value->getType(
            ) === PeriodicityConstant::EVERY_MONTH_SAME_DAY || $typePeriodicity === PeriodicityConstant::EVERY_MONTH_SAME_WEEK_DAY) {
            if ($entryEndTime->diffInMonths($endPeriodicity) < 1) {
                $this->context->buildViolation('periodicity.constraint.every_month')
                    ->addViolation();

                return;
            }
        }

        /**
         * En répétition par année, il y doit y avoir au moins un an de différence entre la fin de la périodicité
         * et la fin de la réservation
         */
        if ($value->getType() === PeriodicityConstant::EVERY_YEAR) {
            if ($entryEndTime->diffInYears($endPeriodicity) < 1) {
                $this->context->buildViolation('periodicity.constraint.every_year')
                    ->addViolation();

                return;
            }
        }

    }
}
