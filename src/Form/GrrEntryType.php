<?php

namespace App\Form;

use App\Entity\GrrEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startTime')
            ->add('endTime')
            ->add('entryType')
            ->add('repeatId')
            ->add('roomId')
            ->add('timestamp')
            ->add('createBy')
            ->add('beneficiaireExt')
            ->add('beneficiaire')
            ->add('name')
            ->add('type')
            ->add('description')
            ->add('statutEntry')
            ->add('optionReservation')
            ->add('overloadDesc')
            ->add('moderate')
            ->add('jours')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GrrEntry::class,
        ]);
    }
}
