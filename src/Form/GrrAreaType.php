<?php

namespace App\Form;

use App\Entity\GrrArea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('areaName')
            ->add('access')
            ->add('orderDisplay')
            ->add('ipAdr')
            ->add('morningstartsArea')
            ->add('eveningendsArea')
            ->add('resolutionArea')
            ->add('eveningendsMinutesArea')
            ->add('weekstartsArea')
            ->add('twentyfourhourFormatArea')
            ->add('calendarDefaultValues')
            ->add('enablePeriods')
            ->add('displayDays')
            ->add('idTypeParDefaut')
            ->add('dureeMaxResaArea')
            ->add('dureeParDefautReservationArea')
            ->add('maxBooking')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GrrArea::class,
        ]);
    }
}
