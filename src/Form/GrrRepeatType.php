<?php

namespace App\Form;

use App\Entity\GrrRepeat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrRepeatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startTime')
            ->add('endTime')
            ->add('repType')
            ->add('endDate')
            ->add('repOpt')
            ->add('roomId')
            ->add('timestamp')
            ->add('createBy')
            ->add('beneficiaireExt')
            ->add('beneficiaire')
            ->add('name')
            ->add('type')
            ->add('description')
            ->add('repNumWeeks')
            ->add('overloadDesc')
            ->add('jours')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GrrRepeat::class,
        ]);
    }
}
