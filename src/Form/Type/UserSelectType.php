<?php

namespace App\Form\Type;

use App\Entity\Area;
use App\Entity\Security\User;
use App\Repository\AreaRepository;
use App\Repository\Security\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSelectType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'label' => 'entry.form.user.select.label',
                'class' => User::class,
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => $this->userRepository->getQueryBuilder(),
                'attr' => ['class' => 'custom-control custom-checkbox my-1 mr-sm-2'],
            ]
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
