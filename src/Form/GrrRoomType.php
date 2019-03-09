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
            ->add(
                'capacity',
                IntegerType::class,
                ['help' => "Nombre de personnes maximum autorisé dans la salle (0 s'il ne s'agit pas d'une salle)"]
            )
            ->add(
                'maxBooking',
                IntegerType::class,
                [
                    'help' => "Nombre max. de réservations par utilisateur (-1 si pas de restriction)",
                ]
            )
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
                FileType::class,
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
            ->add(
                'delaisMaxResaRoom',
                IntegerType::class,
                [
                    'help' => "Nombre max. de réservations par utilisateur (-1 si pas de restriction) ",
                ]
            )
            ->add(
                'delaisMinResaRoom',
                IntegerType::class,
                [
                    'help' => "Temps en minutes en-deçà duquel l'utilisateur ne peut pas réserver ou modifier une réservation (0 si pas de restriction).",
                ]
            )
            ->add(
                'allowActionInPast',
                CheckboxType::class,
                [
                    'help' => "Permettre les réservations dans le passé ainsi que les modifications/suppressions de réservations passées.",
                ]
            )
            ->add(
                'orderDisplay',
                IntegerType::class,
                [
                    'label' => 'Ordre d\'affichage',
                ]
            )
            ->add('delaisOptionReservation')
            ->add(
                'dontAllowModify',
                CheckboxType::class,
                [
                    'help' => "Ne pas permettre aux utilisateurs de modifier ou de supprimer leurs propres réservations.",
                ]
            )
            ->add(
                'typeAffichageReser',
                ChoiceType::class,
                [
                    'choices' => RoomData::typeAffichageReser(),
                ]
            )
            ->add(
                'moderate',
                CheckboxType::class,
                [
                    'label' => "Modérer les réservations de cette ressource",
                    'help' => "Une réservation n'est effective qu'après validation par un administrateur du domaine ou un gestionnaire de la ressource.",
                ]
            )
            ->add(
                'quiPeutReserverPour',
                ChoiceType::class,
                [
                    'choices' => array_flip(RoomData::qui_peut_reserver_pour()),
                ]
            )
            ->add(
                'activeRessourceEmpruntee',
                CheckboxType::class,
                [
                    'help' => "(activer fonctionalite ressource empruntee restituee)",
                ]
            )
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
