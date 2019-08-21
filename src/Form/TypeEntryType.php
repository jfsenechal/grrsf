<?php

namespace App\Form;

use App\Entity\EntryType;
use App\Settings\SettingsTypeEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'typeArea.form.name.label',
                ]
            )
            ->add(
                'orderDisplay',
                IntegerType::class,
                [
                    'label' => 'typeArea.form.orderDisplay.label',
                ]
            )
            ->add(
                'color',
                ColorType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'letter',
                ChoiceType::class,
                [
                    'label' => 'typeArea.form.typeLetter.label',
                    'choices' => array_flip(SettingsTypeEntry::lettres()),
                ]
            )
            ->add(
                'disponible',
                ChoiceType::class,
                [
                    'label' => 'typeArea.form.disponible.label',
                    'choices' => array_flip(SettingsTypeEntry::disponibleFor()),
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => EntryType::class,
            ]
        );
    }
}
