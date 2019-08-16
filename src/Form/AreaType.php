<?php

namespace App\Form;

use App\Entity\Area;
use App\GrrData\GrrConstants;
use App\Provider\DateProvider;
use App\Settings\SettingsArea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'area.form.name.label',
                ]
            )
            ->add(
                'isPrivate',
                CheckboxType::class,
                [
                    'label' => 'area.form.access.label',
                    'required' => false,
                ]
            )
            ->add(
                'orderDisplay',
                IntegerType::class,
                [
                    'label' => 'area.form.orderDisplay.label',
                ]
            )
            ->add(
                'weekStart',
                ChoiceType::class,
                [
                    'choices' => array_flip(DateProvider::getNamesDaysOfWeek()),
                    'label' => 'area.form.weekstartsArea.label',
                ]
            )
            ->add(
                'daysOfWeekToDisplay',
                ChoiceType::class,
                [
                    'label' => 'area.form.displayDays.label',
                    'choices' => array_flip(DateProvider::getNamesDaysOfWeek()),
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
            ->add(
                'startTime',
                ChoiceType::class,
                [
                    'label' => 'area.form.morningstartsArea.label',
                    'choices' => DateProvider::getHours(),
                ]
            )
            ->add(
                'endTime',
                ChoiceType::class,
                [
                    'label' => 'area.form.eveningendsArea.label',
                    'choices' => DateProvider::getHours(),
                ]
            )
            ->add(
                'minutesToAddToEndTime',
                IntegerType::class,
                [
                    'label' => 'area.form.eveningendsMinutesArea.label',
                ]
            )
            ->add(
                'durationTimeSlot',
                IntegerType::class,
                [
                    'label' => 'area.form.resolutionArea.label',
                    'help' => 'area.form.resolutionArea.help',
                ]
            )
            ->add(
                'durationDefaultEntry',
                IntegerType::class,
                [
                    'label' => 'area.form.dureeParDefautReservationArea.label',
                    'help' => 'area.form.dureeParDefautReservationArea.help',
                ]
            )
            ->add(
                'durationMaximumEntry',
                IntegerType::class,
                [
                    'label' => 'area.form.dureeMaxResaArea.label',
                    'help' => 'area.form.dureeMaxResaArea.help',
                ]
            )
            ->add(
                'is24HourFormat',
                ChoiceType::class,
                [
                    'label' => 'area.form.twentyfourhourFormatArea.label',
                    'choices' => array_flip(SettingsArea::getAffichageFormat()),
                    'multiple' => false,
                    'expanded' => true,
                ]
            )
            ->add(
                'maxBooking',
                IntegerType::class,
                [
                    'label' => 'area.form.maxBooking.label',
                    'help' => 'area.form.maxBooking.help',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Area::class,
            ]
        );
    }
}
