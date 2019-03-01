<?php

namespace App\Form;

use App\Entity\GrrArea;
use App\GrrData\DateUtils;
use App\GrrData\GrrConstants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'areaName',
                TextType::class,
                [
                    'label' => 'Nom',
                ]
            )
            ->add(
                'access',
                CheckboxType::class,
                [
                    'label' => 'Accès restreint',
                ]
            )
            ->add(
                'orderDisplay',
                IntegerType::class,
                [
                    'label' => 'Ordre d\'affichage',
                ]
            )
            ->add(
                'ipAdr',
                TextType::class,
                [
                    'help' => 'Domaine par défaut pour l\'adresse IP client suivante :	',
                ]
            )
            ->add(
                'weekstartsArea',
                ChoiceType::class,
                [
                    'choices' => array_flip(DateUtils::getJoursSemaine()),
                    'label' => 'Début de la semaine',
                ]
            )
            ->add(
                'displayDays',
                ChoiceType::class,
                [
                    'choices' => array_flip(DateUtils::getJoursSemaine()),
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
            ->add(
                'enablePeriods',
                ChoiceType::class,
                [
                    'label' => 'Configuration du type de créneaux Help',
                    'choices' => GrrConstants::PERIOD,
                    'multiple' => false,
                    'expanded' => true,
                ]
            )
            ->add(
                'morningstartsArea',
                ChoiceType::class,
                [
                    'choices' => DateUtils::getHeures(),
                    'label' => "Heure de début de journée",
                ]
            )
            ->add(
                'eveningendsArea',
                ChoiceType::class,
                [
                    'choices' => DateUtils::getHeures(),
                    'label' => "Heure de fin de journée",
                ]
            )
            ->add(
                'eveningendsMinutesArea',
                IntegerType::class,
                [
                    'help' => "Nombre de minutes à ajouter à l'heure de fin de journée pour avoir la fin réelle d'une journée.",
                ]
            )
            ->add(
                'resolutionArea',
                IntegerType::class,
                [
                    'help' => "Plus petit bloc réservable, en secondes (1800 secondes = 1/2 heure)",
                ]
            )
            ->add(
                'dureeParDefautReservationArea',
                IntegerType::class,
                [
                    'help' => "Durée par défaut d'une réservation, en secondes (doit être un multiple de la valeur précédente)",
                ]
            )
            ->add(
                'twentyfourhourFormatArea',
                ChoiceType::class,
                [
                    'choices' => array_flip(DateUtils::getAffichageFormat()),
                    'multiple' => false,
                    'expanded' => true,
                    'help' => "Format d'affichage du temps",
                ]
            )
            ->add(
                'dureeMaxResaArea',
                IntegerType::class,
                [
                    'help' => "Durée maximale en minutes (une journée = 1440 minutes) pour une réservation (-1 si pas de restriction)",
                ]
            )
            ->add(
                'maxBooking',
                IntegerType::class,
                [
                    'help' => "Nombre max. de réservations par utilisateur (-1 si pas de restriction) - Pour toutes les ressources du domaine :	",
                ]
            )->add('calendarDefaultValues')
            ->add('idTypeParDefaut');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => GrrArea::class,
            ]
        );
    }
}
