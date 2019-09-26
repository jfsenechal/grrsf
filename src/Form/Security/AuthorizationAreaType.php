<?php

namespace App\Form\Security;

use App\Form\Type\UserSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuthorizationAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'users',
                UserSelectType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            );
    }

    public function getParent()
    {
        return AuthorizationType::class;
    }
}
