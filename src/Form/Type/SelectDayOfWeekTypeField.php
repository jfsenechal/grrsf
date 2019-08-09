<?php

namespace App\Form\Type;

use App\GrrData\DateUtils;
use App\GrrData\PeriodicityConstant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class SelectDayOfWeekTypeField extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $days = DateUtils::getDays();
        $weeks = PeriodicityConstant::LIST_WEEKS_REPEAT;

        $builder
            ->add(
                'days',
                ChoiceType::class,
                [
                    'choices' => array_flip($days),
                    'label' => 'periodicity.form.selectdays.label',
                    'help' => 'entry.form.duration_unit.help',
                    'mapped' => false,
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
            ->add(
                'weeks',
                ChoiceType::class,
                [
                    'choices' => array_flip($weeks),
                    'label' => 'periodicity.form.selectweeksrepeat.label',
                    'help' => 'entry.form.duration_unit.help',
                    'mapped' => false,
                    'multiple' => false,
                    'expanded' => true,
                ]
            );
    }
}
