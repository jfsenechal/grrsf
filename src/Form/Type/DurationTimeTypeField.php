<?php

namespace App\Form\Type;

use App\Model\DurationModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DurationTimeTypeField extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = DurationModel::getUnitsTime();
        $scale = $options['scale'];

        $builder
            ->add(
                'time',
                NumberType::class,
                [
                    'label' => 'entry.form.duration_time.label',
                    'scale' => $scale,
                ]
            )
            ->add(
                'unit',
                ChoiceType::class,
                [
                    'choices' => array_flip($choices),
                    //  'label' => 'entry.form.duration_unit.label',
                    'label' => ' ',
                    'help' => 'entry.form.duration_unit.help',
                ]
            )
            ->add(
                'full_day',
                CheckboxType::class,
                [
                    'label' => 'entry.form.full_day.label',
                    'help' => 'entry.form.full_day.help',
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('scale', 0);
    }
}
