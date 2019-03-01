<?php

namespace App\Form;

use App\Entity\GrrRoom;
use App\GrrData\RoomData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    'label' => 'Nom',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'attr' => ['cols' => 5],
                ]
            )
            ->add('capacity')
            ->add('maxBooking')
            ->add(
                'statutRoom',
                CheckboxType::class,
                [
                    'label' => "Déclarer cette ressource temporairement indisponible",
                    'help' => "Les réservations sont alors impossibles. La restriction ne s'applique pas aux gestionnaires de la ressource.",
                ]
            )
            ->add(
                'showFicRoom',
                CheckboxType::class,
                [
                    'label' => "Fiche de présentation",
                    'help' => "Rendre visible la fiche de présentation de la ressource dans l'interface publique.",
                ]
            )
            ->add(
                'pictureRoom',
                CheckboxType::class,
                [
                    'label' => 'Image',
                    'help' => "Supprimer l'image actuelle de la ressource : (aucun)",
                ]
            )
            ->add('commentRoom')
            ->add(
                'showComment',
                CheckboxType::class,
                [
                    'help' => "Afficher la description complète dans le titre des plannings.",
                ]
            )
            ->add('delaisMaxResaRoom')
            ->add('delaisMinResaRoom')
            ->add('allowActionInPast')
            ->add(
                'orderDisplay',
                IntegerType::class,
                [
                    'label' => 'Ordre d\'affichage',
                ]
            )
            ->add('delaisOptionReservation')
            ->add('dontAllowModify')
            ->add('typeAffichageReser')
            ->add('moderate')
            ->add('quiPeutReserverPour')
            ->add('activeRessourceEmpruntee')
            ->add(
                'whoCanSee',
                ChoiceType::class,
                [
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
