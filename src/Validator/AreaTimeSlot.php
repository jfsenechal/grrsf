<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Vérifie que l'heure de début et de fin de l'entry respecte les heures d'ouvertures et fermetures de l'are
 * @Annotation
 */
class AreaTimeSlot extends Constraint
{
    /**
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'entry.constraint.areatime';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
