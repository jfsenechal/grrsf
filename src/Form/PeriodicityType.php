<?php

namespace App\Form;

use App\Entity\Periodicity;
use App\Form\Type\SelectDayOfWeekTypeField;
use App\GrrData\PeriodicityConstant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodicityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = PeriodicityConstant::getTypesPeriodicite();

        $builder
            ->add(
                'end_time',
                DateTimeType::class,
                [
                    'label' => 'periodicity.form.endtime.label',
                ]
            )
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => array_flip($types),
                    'multiple' => false,
                    'expanded' => true,
                    'data' => 0,
                ]
            )
            ->add(
                'weeks',
                SelectDayOfWeekTypeField::class,
                [
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Periodicity::class,
            ]
        );
    }
}
