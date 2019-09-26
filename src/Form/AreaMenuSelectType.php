<?php

namespace App\Form;

use App\EventSubscriber\AddRoomFieldSubscriber;
use App\Form\Type\AreaSelectType;
use App\Navigation\MenuSelect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaMenuSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'area',
                AreaSelectType::class,
                [
                    'required' => true,
                ]
            )
            ->addEventSubscriber(new AddRoomFieldSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data' => MenuSelect::class,
            ]
        );
    }
}
