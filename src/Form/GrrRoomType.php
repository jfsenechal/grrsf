<?php

namespace App\Form;

use App\Entity\GrrRoom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('areaId')
            ->add('roomName')
            ->add('description')
            ->add('capacity')
            ->add('maxBooking')
            ->add('statutRoom')
            ->add('showFicRoom')
            ->add('pictureRoom')
            ->add('commentRoom')
            ->add('showComment')
            ->add('delaisMaxResaRoom')
            ->add('delaisMinResaRoom')
            ->add('allowActionInPast')
            ->add('orderDisplay')
            ->add('delaisOptionReservation')
            ->add('dontAllowModify')
            ->add('typeAffichageReser')
            ->add('moderate')
            ->add('quiPeutReserverPour')
            ->add('activeRessourceEmpruntee')
            ->add('whoCanSee')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GrrRoom::class,
        ]);
    }
}
