<?php

namespace App\Form\Security;

use App\Entity\Security\User;
use App\EventSubscriber\AddRoomDefaultFieldSubscriber;
use App\Form\Type\AreaSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'user.form.name.label',
                ]
            )
            ->add(
                'first_name',
                TextType::class,
                [
                    'label' => 'user.form.first_name.label',
                    'required' => false,
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                ]
            )
            ->add(
                'area_default',
                AreaSelectType::class,
                [
                    'label' => 'user.form.area.label',
                    'required' => false,
                    'placeholder' => 'area.form.select.placeholder',
                ]
            )
            ->addEventSubscriber(new AddRoomDefaultFieldSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
