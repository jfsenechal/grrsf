<?php

namespace App\Form\Security;

use App\Form\Type\UserSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AuthorizationRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('area')
            ->remove('rooms')
            ->add('users', UserSelectType::class);
    }

    public function getParent()
    {
        return AuthorizationType::class;
    }
}
