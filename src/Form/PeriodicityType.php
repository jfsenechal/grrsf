<?php

namespace App\Form;

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
                'type',
                ChoiceType::class,
                [
                    'choices' => array_flip($types),
                    'multiple' => false,
                    'expanded' => true,
                ]
            )
            ->add(
                'days',
                SelectDayOfWeekTypeField::class,
                [
                    'attr' => ['class' => 'd-none'],
                ]
            )
            ->add(
                'end_periodicity',
                DateTimeType::class,
                [
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            ]
        );
    }
}
