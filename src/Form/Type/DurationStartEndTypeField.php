<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

class DurationStartEndTypeField extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'endTime',
                DateTimeType::class,
                [
                    'label' => 'entry.form.endTime.label',
                    'help' => 'entry.form.endTime.help',
                ]
            )
         /*   ->add(
                'inter',
                DateIntervalType::class,
                [

                ]
            )*/;
    }
}
