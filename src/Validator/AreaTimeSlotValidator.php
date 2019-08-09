<?php

namespace App\Validator;

use App\Entity\Entry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AreaTimeSlotValidator extends ConstraintValidator
{
    /**
     * @param Entry      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Entry) {
            throw new \UnexpectedValueException($value, 'Entry');
        }

        $area = $value->getArea();

        if ($value->getStartTime()->format('H:i') > $area->getMorningstartsArea()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value1 }}', 'début')
                ->setParameter('{{ value2 }}', 'début')
                ->addViolation();
        }

        if ($value->getEndTime()->format('H:i') > $area->getEveningendsArea()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value1 }}', 'fin')
                ->setParameter('{{ value2 }}', 'fin')
                ->addViolation();
        }
    }
}
