<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AreaTimeSlot extends Constraint
{
    /**
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'L\'heure de "{{value1}}" dépasse l\'heure de "{{value2}}" de l\'area.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
