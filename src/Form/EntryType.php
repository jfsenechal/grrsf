<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Form\Type\EntryTypeField;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var AreaRepository
     */
    private $areaRepository;

    public function __construct(RoomRepository $roomRepository, AreaRepository $areaRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->areaRepository = $areaRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('startTime', DateTimeType::class)
            ->add('endTime', DateTimeType::class)
            ->add('entryType')
            ->add(
                'area',
                EntityType::class,
                [
                    'required' => false,
                    'placeholder' => 'Area',
                    'class' => Area::class,
                    'query_builder' => $this->areaRepository->getQueryBuilder(),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                    'mapped' => false,
                ]
            )
            /*   ->add(
                   'roomId',
                   EntityType::class,
                   [
                       'required' => false,
                       'placeholder' => 'Room',
                       'class' => Room::class,
                       'query_builder' => $this->roomRepository->getQueryBuilder(),
                       'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                   ]
               )*/
            ->add(
                'room',
                EntityType::class,
                [
                    'required' => false,
                    'class'=>Room::class,
                    'placeholder' => 'Room',
                    'query_builder' => $this->roomRepository->getQueryBuilder(),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->add('createBy')
            ->add('beneficiaireExt')
            ->add('beneficiaire')
            ->add('type', EntryTypeField::class)
            ->add('description')
            ->add('statutEntry')
            ->add('optionReservation')
            ->add('overloadDesc')
            ->add('moderate')
            ->add('jours');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Entry::class,
            ]
        );
    }
}
