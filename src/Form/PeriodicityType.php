<?php

namespace App\Form;

use App\Entity\Periodicity;
use App\GrrData\DateUtils;
use App\GrrData\PeriodicityConstant;
use App\Provider\DateProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodicityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = PeriodicityConstant::getTypesPeriodicite();
        $days = DateProvider::getNamesDaysOfWeek();
        $weeks = PeriodicityConstant::LIST_WEEKS_REPEAT;

        $builder
            ->add(
                'endTime',
                DateType::class,
                [
                    'label' => 'periodicity.form.endtime.label',
                ]
            )
            ->add(
                'type',
                ChoiceType::class,
                [
                    'label' => 'periodicity.type.label',
                    'choices' => array_flip($types),
                    'multiple' => false,
                    'expanded' => true,
                    'required' => false,
                ]
            )
            ->add(
                'days',
                ChoiceType::class,
                [
                    'choices' => array_flip($days),
                    'label' => 'periodicity.form.selectdays.label',
                    'help' => 'entry.form.duration_unit.help',
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
            ->add(
                'repeatWeek',
                ChoiceType::class,
                [
                    'choices' => array_flip($weeks),
                    'label' => 'periodicity.form.selectweeksrepeat.label',
                    'required' => false,
                    'multiple' => false,
                    'expanded' => true,
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
