<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodicityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = ['None', 'Chaque jour', 'Chaque semaine', 'Chaque mois', 'Chaque année'];
        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => array_flip($types),
                    'multiple' => false,
                    'expanded' => true,
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
