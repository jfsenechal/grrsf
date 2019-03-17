<?php

namespace App\Form;

use App\Entity\GrrArea;
use App\Entity\GrrRoom;
use App\Repository\GrrAreaRepository;
use App\Repository\GrrRoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaMenuSelectType extends AbstractType
{
    /**
     * @var GrrAreaRepository
     */
    private $grrAreaRepository;
    /**
     * @var GrrRoomRepository
     */
    private $grrRoomRepository;

    public function __construct(GrrAreaRepository $grrAreaRepository, GrrRoomRepository $grrRoomRepository)
    {
        $this->grrAreaRepository = $grrAreaRepository;
        $this->grrRoomRepository = $grrRoomRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $area = $options['area'];

        $builder
            ->add(
                'area',
                EntityType::class,
                [
                    'class' => GrrArea::class,
                    'query_builder' => $this->grrAreaRepository->getQueryBuilder(),
                    'required' => true,
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->add(
                'room',
                EntityType::class,
                [
                    'class' => GrrRoom::class,
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

        $resolver->setAllowedTypes('area', GrrArea::class);
    }


}
