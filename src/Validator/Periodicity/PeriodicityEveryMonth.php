<?php

namespace App\Validator\Periodicity;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PeriodicityEveryMonth extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    /**
     * @var string
     */
    public $message = 'The value "{{ value }}" is not valid.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
