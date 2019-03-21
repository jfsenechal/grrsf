<?php

namespace App\Form;

use App\Service\SettingConstants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(SettingConstants::TITLE_HOME_PAGE, TextType::class)
            ->add(SettingConstants::MESSAGE_HOME_PAGE, TextType::class)
            ->add(SettingConstants::COMPANY, TextType::class)
            ->add(SettingConstants::GRR_URL, TextType::class)
            ->add(SettingConstants::WEBMASTER_NAME, TextType::class)
            ->add(SettingConstants::WEBMASTER_EMAIL, TextType::class)
            ->add(SettingConstants::TECHNICAL_SUPPORT_EMAIL, TextType::class)
            ->add(SettingConstants::NB_CALENDAR, IntegerType::class)
            ->add(SettingConstants::MESSAGE_ACCUEIL, TextareaType::class)
            ->add(SettingConstants::BEGIN_BOOKINGS, DateType::class)
            ->add(SettingConstants::END_BOOKINGS, DateType::class)
            ->add(
                SettingConstants::DEFAULT_LANGUAGE,
                ChoiceType::class,
                [
                    'choices' => [],
                ]
            )
            ->add(SettingConstants::COMPANY, TextType::class)
            ->add(SettingConstants::COMPANY, TextType::class)
            ->add(SettingConstants::COMPANY, TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

            ]
        );
    }
}
