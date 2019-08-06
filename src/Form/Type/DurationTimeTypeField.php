<?php


namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class DurationTimeTypeField extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = ['minute(s)', 'heure(s)', 'jour(s)', 'semaine(s)'];

        $builder
            ->add(
                'duration_time',
                IntegerType::class,
                [
                    'label' => 'entry.form.duration_time.label',
                    'help' => 'entry.form.duration_time.help',

                ]
            )
            ->add(
                'duration_unit',
                ChoiceType::class,
                [
                    'choices' => array_flip($choices),
                    'label' => 'entry.form.duration_unit.label',
                    'help' => 'entry.form.duration_unit.help',
                ]
            )
            ->add(
                'full_day',
                CheckboxType::class,
                [
                    'label' => 'entry.form.full_day.label',
                    'help' => 'entry.form.full_day.help',
                ]
            );
    }
}