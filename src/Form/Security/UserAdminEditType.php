<?php

namespace App\Form\Security;

use App\Entity\Security\User;
use App\Security\SecurityRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAdminEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'label' => 'user.form.roles.label',
                    'choices' => SecurityRole::getRoles(),
                    'required' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'attr' => ['class' => 'custom-control custom-checkbox my-1 mr-sm-2'],
                ]
            )
            ->add(
                'isEnabled',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'user.form.isEnabled.label',
                    'help' => 'user.form.isEnabled.help',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }

    public function getParent()
    {
        return UserEditType::class;
    }
}
