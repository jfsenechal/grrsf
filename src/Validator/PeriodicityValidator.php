<?php

namespace App\Validator;

use App\Periodicity\PeriodicityConstant;
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

        if (!$value instanceof \App\Entity\Periodicity) {
            throw new \UnexpectedValueException($value, 'Periodicity');
        }

        $entry = $value->getEntry();

        if ($value->getEndTime() <= $entry->getEndTime()) {
            $this->context->buildViolation('periodicity.constraint.end_time')
                ->addViolation();
        }

        if ($value->getType() === PeriodicityConstant::EVERY_DAY) {
            if ($entry->getStartTime()->format('d') !== $entry->getEndTime()->format('d')) {
                $this->context->buildViolation('periodicity.constraint.every_day')
                    ->addViolation();
            }
        }

        if ($value->getType() === PeriodicityConstant::EVERY_MONTH_SAME_DAY || $value->getType(
            ) === PeriodicityConstant::EVERY_MONTH_SAME_WEEK_DAY) {
            if ($entry->getEndTime()->format('Y-m') >= $value->getEndTime()->format('Y-m')) {
                $this->context->buildViolation('periodicity.constraint.every_month')
                    ->addViolation();
            }
        }

        if ($value->getType() === PeriodicityConstant::EVERY_YEAR) {
            if ($entry->getEndTime()->format('Y') >= $value->getEndTime()->format('Y')) {
                $this->context->buildViolation('periodicity.constraint.every_year')
                    ->addViolation();
            }
        }

    }
}
