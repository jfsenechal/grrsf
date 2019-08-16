<?php

namespace App\Form\Security;

use App\Entity\Security\UserManagerResource;
use App\EventSubscriber\AddAreaFieldSubscriber;
use App\EventSubscriber\AddRoomFieldSubscriber;
use App\EventSubscriber\AddUserFieldSubscriber;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use App\Repository\Security\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserManagerResourceType extends AbstractType
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        UserRepository $userRepository
    ) {
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'is_area_administrator',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->addEventSubscriber(new AddAreaFieldSubscriber($this->areaRepository))
            ->addEventSubscriber(new AddRoomFieldSubscriber($this->roomRepository))
            ->addEventSubscriber(new AddUserFieldSubscriber($this->userRepository));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => UserManagerResource::class,
            ]
        );
    }
}
