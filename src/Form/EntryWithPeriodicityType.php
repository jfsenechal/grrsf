<?php

namespace App\Form;

use App\Entity\Entry;
use App\EventSubscriber\AddDurationFieldSubscriber;
use App\EventSubscriber\AddRoomFieldSubscriber;
use App\Factory\DurationFactory;
use App\Form\Type\AreaSelectType;
use App\Form\Type\EntryTypeSelectField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryWithPeriodicityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('periodicity', PeriodicityType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Entry::class,
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'periodicity';
    }


    public function getParent()
    {
        return EntryType::class;
    }
}
