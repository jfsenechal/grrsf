<?php

namespace App\Form\Security;

use App\EventSubscriber\AddUserFieldSubscriber;
use App\Form\Type\AreaHiddenType;
use App\Repository\Security\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AuthorizationAreaType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('area', AreaHiddenType::class)
            //->addEventSubscriber(new AddAreaFieldSubscriber())
            ->addEventSubscriber(new AddUserFieldSubscriber($this->userRepository));
    }

    public function getParent()
    {
        return AuthorizationType::class;
    }
}
