<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Room;
use App\Events\AddEmailFieldListener;
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
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
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
                    'query_builder' => $this->areaRepository->getQueryBuilder(),
                    'required' => true,
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                    'mapped'=>false
                ]
            )
            ->add(
                'rooms',
                EntityType::class,
                [
                    'class' => Room::class,
                    'required' => false,
                    'placeholder' => 'menu.select.room',
                    'query_builder' => $this->roomRepository->getRoomsByAreaQueryBuilder($area),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->addEventSubscriber(new AddEmailFieldListener());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data' => Area::class,
                'area' => null,
            )
        );

        $resolver->setAllowedTypes('area', [Area::class]);
    }


}
