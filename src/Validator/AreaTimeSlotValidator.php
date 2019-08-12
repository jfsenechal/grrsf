<?php

namespace App\Validator;

use App\Entity\Entry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AreaTimeSlotValidator extends ConstraintValidator
{
    /**
     * @param Entry $entry
     * @param Constraint $constraint
     */
    public function validate($entry, Constraint $constraint)
    {
        if (!$entry instanceof Entry) {
            throw new \UnexpectedValueException($entry, 'Entry');
        }

        $area = $entry->getArea();

        if ($entry->getStartTime()->format('H:i') < $area->getMorningstartsArea()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{param1}}', 'début')
                ->setParameter('{{param2}}', 'grande')
                ->setParameter('{{param3}}', 'd\'ouverture')
                ->addViolation();
        }

        if ($entry->getEndTime()->format('H:i') > $area->getEveningendsArea()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{param1}}', 'fin')
                ->setParameter('{{param2}}', 'petite')
                ->setParameter('{{param3}}', 'de fermeture')
                ->addViolation();
        }
    }
}
