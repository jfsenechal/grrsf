<?php

namespace App\Form\Security;

use App\EventSubscriber\AddAreaFieldSubscriber;
use App\EventSubscriber\AddUserFieldSubscriber;
use App\Model\AuthorizationAreaModel;
use App\Repository\Security\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder->add('areaAdministrator', HiddenType::class);
        $builder
            ->addEventSubscriber(new AddAreaFieldSubscriber())
            ->addEventSubscriber(new AddUserFieldSubscriber($this->userRepository));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AuthorizationAreaModel::class,
            ]
        );
    }
}