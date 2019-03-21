<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Room;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaMenuSelectType extends AbstractType
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(AreaRepository $areaRepository, RoomRepository $roomRepository)
    {
        $this->grrAreaRepository = $areaRepository;
        $this->grrRoomRepository = $roomRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $area = $options['area'];

        $builder
            ->add(
                'area',
                EntityType::class,
                [
                    'class' => Area::class,
                    'query_builder' => $this->grrAreaRepository->getQueryBuilder(),
                    'required' => true,
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->add(
                'room',
                EntityType::class,
                [
                    'class' => Room::class,
                    'required' => false,
                    'placeholder' => 'menu.select.room',
                    'query_builder' => $this->grrRoomRepository->getRoomsByAreaQueryBuilder($area),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            );
        //->addEventSubscriber(new AddEmailFieldListener());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'area' => null,
            )
        );

        $resolver->setAllowedTypes('area', Area::class);
    }


}
