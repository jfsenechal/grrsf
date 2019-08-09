<?php

namespace App\Form;

use App\Entity\Periodicity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Periodicity1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('end_time')
            ->add('every_day', CheckboxType::class)
            ->add('every_year')
            ->add('every_week')
            ->add('number_week')
            ->add('week_days')
            ->add('every_month_same_day')
            ->add('every_month_same_week_day')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Periodicity::class,
        ]);
    }
}
