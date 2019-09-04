<?php

namespace App\Form\Security;

use App\EventSubscriber\AddAreaFieldSubscriber;
use App\EventSubscriber\AddRoomFieldSubscriber;
use App\Model\AuthorizationModel;
use App\Repository\RoomRepository;
use App\Repository\Security\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorizationType extends AbstractType
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(
        UserRepository $userRepository,
        RoomRepository $roomRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
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
                'placeholder' => 'none.male',
                'required' => false,
                'multiple' => false,
                'expanded' => true,
            ]
        );
        $builder
            ->addEventSubscriber(new AddAreaFieldSubscriber(false))
            ->addEventSubscriber(new AddRoomFieldSubscriber('rooms', true, true, false));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AuthorizationModel::class,
            ]
        );
    }
}
