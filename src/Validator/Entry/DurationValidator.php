<?php

namespace App\Validator\Entry;

use App\Model\DurationModel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Uniquement un float pour les heures
 * Class DurationValidator.
 */
class DurationValidator extends ConstraintValidator
{
    /**
     * @param DurationModel $value
     * @param Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof DurationModel) {
            throw new \Exception('Not valid value');
        }

        $unit = $value->getUnit();
        $time = $value->getTime();
        $whole = (int)($time);
        $fraction = $time - $whole;

        if (DurationModel::UNIT_TIME_HOURS !== $unit) {
            if (0.0 !== $fraction) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
