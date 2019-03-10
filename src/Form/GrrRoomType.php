<?php

namespace App\Form;

use App\Entity\GrrRoom;
use App\GrrData\RoomData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'roomName',
                TextType::class,
                [
                    'label' => 'room.form.nom.label',
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'room.form.description.label',
                ]
            )
            ->add(
                'capacity',
                IntegerType::class,
                [
                    'label' => 'room.form.capacity.label',
                    'help' => 'room.form.capacity.help',
                ]
            )
            ->add(
                'maxBooking',
                IntegerType::class,
                [
                    'help' => 'room.form.maxBooking.help',
                ]
            )
            ->add(
                'statutRoom',
                CheckboxType::class,
                [
                    'label' => 'room.form.statutRoom.label',
                    'help' => 'room.form.statutRoom.help',
                ]
            )
            ->add(
                'showFicRoom',
                CheckboxType::class,
                [
                    'label' => 'room.form.showFicRoom.label',
                    'help' => 'room.form.showFicRoom.help',
                ]
            )
            ->add(
                'pictureRoom',
                FileType::class,
                [
                    'label' => 'room.form.pictureRoom.label',
                    'help' => 'room.form.pictureRoom.help',
                ]
            )
            ->add(
                'commentRoom',
                TextareaType::class,
                [
                    'label' => 'room.form.commentRoom.label',
                    'attr' => ['height' => '80px'],
                ]
            )
            ->add(
                'showComment',
                CheckboxType::class,
                [
                    'label' => 'room.form.showComment.label',
                ]
            )
            ->add(
                'delaisMaxResaRoom',
                IntegerType::class,
                [
                    'label' => 'room.form.delaisMaxResaRoom.label',
                    'help' => 'room.form.delaisMaxResaRoom.help',
                ]
            )
            ->add(
                'delaisMinResaRoom',
                IntegerType::class,
                [
                    'label' => 'room.form.delaisMinResaRoom.label',
                    'help' => 'room.form.delaisMinResaRoom.help',
                ]
            )
            ->add(
                'allowActionInPast',
                CheckboxType::class,
                [
                    'label' => 'room.form.allowActionInPast.label',
                    'help' => 'room.form.allowActionInPast.help',
                ]
            )
            ->add(
                'orderDisplay',
                IntegerType::class,
                [
                    'label' => 'room.form.orderDisplay.label',
                ]
            )
            ->add(
                'delaisOptionReservation',
                IntegerType::class,
                [
                    'label' => 'room.form.delaisOptionReservation.label',
                    'help' => 'room.form.delaisOptionReservation.help',
                ]
            )
            ->add(
                'dontAllowModify',
                CheckboxType::class,
                [
                    'label' => 'room.form.dontAllowModify.label',
                    'help' => 'room.form.dontAllowModify.help',
                ]
            )
            ->add(
                'typeAffichageReser',
                ChoiceType::class,
                [
                    'label' => 'room.form.typeAffichageReser.label',
                    'help' => 'room.form.typeAffichageReser.help',
                    'choices' => RoomData::typeAffichageReser(),
                ]
            )
            ->add(
                'moderate',
                CheckboxType::class,
                [
                    'label' => 'room.form.moderate.label',
                    'help' => 'room.form.moderate.help',
                ]
            )
            ->add(
                'quiPeutReserverPour',
                ChoiceType::class,
                [
                    'label' => 'room.form.quiPeutReserverPour.label',
                    'choices' => array_flip(RoomData::qui_peut_reserver_pour()),
                ]
            )
            ->add(
                'activeRessourceEmpruntee',
                CheckboxType::class,
                [
                    'label' => 'room.form.activeRessourceEmpruntee.label',
                ]
            )
            ->add(
                'whoCanSee',
                ChoiceType::class,
                [
                    'label' => 'room.form.whoCanSee.label',
                    'choices' => array_flip(RoomData::whoCanSee()),
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => GrrRoom::class,
            ]
        );
    }
}
