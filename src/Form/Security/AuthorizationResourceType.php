<?php

namespace App\Form\Security;

use App\EventSubscriber\AddAreaFieldSubscriber;
use App\EventSubscriber\AddRoomsFieldSubscriber;
use App\EventSubscriber\AddUserFieldSubscriber;
use App\Model\AuthorizationResourceModel;
use App\Repository\Security\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorizationResourceType extends AbstractType
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
        $builder
            ->addEventSubscriber(new AddAreaFieldSubscriber())
            ->addEventSubscriber(new AddRoomsFieldSubscriber())
            ->addEventSubscriber(new AddUserFieldSubscriber($this->userRepository));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AuthorizationResourceModel::class,
            ]
        );
    }
}
