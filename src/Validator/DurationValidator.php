<?php

namespace App\Validator;

use App\Model\DurationModel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Si day or week uniquement chiffre rond
 * Class DurationValidator
 * @package App\Validator
 */
class DurationValidator extends ConstraintValidator
{
    /**
     * @param DurationModel $value
     * @param Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof DurationModel) {
            throw new \Exception('Not valid value');
        }

        $unit = $value->getUnit();
        $time = (int) $value->getTime();

        if ($unit == DurationModel::UNIT_TIME_WEEKS || $unit == DurationModel::UNIT_TIME_DAYS) {
            if (!is_int($time)) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
