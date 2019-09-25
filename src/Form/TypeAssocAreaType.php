<?php

namespace App\Form;

use App\Model\TypeAssocArea;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeAssocAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'types',
                EntityType::class,
                [
                    'class' => \App\Entity\EntryType::class,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'typeEntry.form.name.label',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => TypeAssocArea::class,
            ]
        );
    }
}
