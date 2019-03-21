<?php

namespace App\Form;

use App\Entity\Area;
use App\GrrData\DateUtils;
use App\GrrData\GrrConstants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaType extends AbstractType
{
    /**
     * @var DateUtils
     */
    private $dateUtils;

    public function __construct(DateUtils $dateUtils)
    {
        $this->dateUtils = $dateUtils;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'areaName',
                TextType::class,
                [
                    'label' => 'area.form.name.label',
                ]
            )
            ->add(
                'access',
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
                'ipAdr',
                TextType::class,
                [
                    'label' => 'area.form.ipAdr.label',
                    'help' => 'area.form.ipAdr.help',
                    'required' => false,
                ]
            )
            ->add(
                'weekstartsArea',
                ChoiceType::class,
                [
                    'choices' => array_flip($this->dateUtils->getJoursSemaine()),
                    'label' => 'area.form.weekstartsArea.label',
                ]
            )
            ->add(
                'displayDays',
                ChoiceType::class,
                [
                    'label' => 'area.form.displayDays.label',
                    'choices' => array_flip($this->dateUtils->getJoursSemaine()),
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
            ->add(
                'enablePeriods',
                ChoiceType::class,
                [
                    'label' => 'area.form.enablePeriods.label',
                    'choices' => array_flip(GrrConstants::PERIOD),
                    'multiple' => false,
                    'expanded' => true,
                ]
            )
            ->add(
                'morningstartsArea',
                ChoiceType::class,
                [
                    'label' => 'area.form.morningstartsArea.label',
                    'choices' => DateUtils::getHeures(),
                ]
            )
            ->add(
                'eveningendsArea',
                ChoiceType::class,
                [
                    'label' => 'area.form.eveningendsArea.label',
                    'choices' => DateUtils::getHeures(),
                ]
            )
            ->add(
                'eveningendsMinutesArea',
                IntegerType::class,
                [
                    'label' => 'area.form.eveningendsMinutesArea.label',
                ]
            )
            ->add(
                'resolutionArea',
                IntegerType::class,
                [
                    'label' => 'area.form.resolutionArea.label',
                    'help' => 'area.form.resolutionArea.help',
                ]
            )
            ->add(
                'dureeParDefautReservationArea',
                IntegerType::class,
                [
                    'label' => 'area.form.dureeParDefautReservationArea.label',
                    'help' => 'area.form.dureeParDefautReservationArea.help',
                ]
            )
            ->add(
                'twentyfourhourFormatArea',
                ChoiceType::class,
                [
                    'label' => 'area.form.twentyfourhourFormatArea.label',
                    'choices' => array_flip($this->dateUtils->getAffichageFormat()),
                    'multiple' => false,
                    'expanded' => true,
                ]
            )
            ->add(
                'dureeMaxResaArea',
                IntegerType::class,
                [
                    'label' => 'area.form.dureeMaxResaArea.label',
                    'help' => 'area.form.dureeMaxResaArea.help',
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
        //->add('calendarDefaultValues')
        // ->add('idTypeParDefaut');
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
