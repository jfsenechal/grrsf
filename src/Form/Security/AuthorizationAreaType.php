<?php

namespace App\Form\Security;

use App\EventSubscriber\AddAreaFieldSubscriber;
use App\EventSubscriber\AddUserFieldSubscriber;
use App\Model\AuthorizationAreaModel;
use App\Repository\Security\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $choices = array_flip(
            [1 => 'authorization.role.area.administrator', 2 => 'authorization.role.resource.administrator']
        );
        $builder->add(
            'role',
            ChoiceType::class,
            [
                'choices' => $choices,
                'label' => 'authorization.area.role.label',
                'multiple' => false,
                'expanded' => true,
            ]
        );
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
