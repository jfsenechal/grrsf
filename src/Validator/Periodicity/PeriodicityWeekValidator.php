<?php

namespace App\Validator\Periodicity;

use App\Periodicity\PeriodicityConstant;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodicityWeekValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $typePeriodicity = $value->getType();

        if (PeriodicityConstant::EVERY_WEEK !== $typePeriodicity) {
            return;
        }

        if (!$value instanceof \App\Entity\Periodicity) {
            throw new \UnexpectedValueException($value, 'Periodicity');
        }

        $daysSelected = $value->getWeekDays();
        $weekRepeat = $value->getWeekRepeat();

        if (count($daysSelected) < 1) {
            $this->context->buildViolation('periodicity.constraint.every_weeks.no_day')
                ->addViolation();
        }

        if(!$weekRepeat) {
            $this->context->buildViolation('periodicity.constraint.every_weeks.no_repeat')
                ->addViolation();
        }

    }
}
