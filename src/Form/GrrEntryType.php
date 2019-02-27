<?php

namespace App\Form;

use App\Entity\GrrArea;
use App\Entity\GrrEntry;
use App\Entity\GrrRoom;
use App\Form\Type\TimestampFieldType;
use App\Form\Type\GrrEntryTypeField;
use App\Repository\GrrAreaRepository;
use App\Repository\GrrRoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrEntryType extends AbstractType
{
    /**
     * @var GrrRoomRepository
     */
    private $grrRoomRepository;
    /**
     * @var GrrAreaRepository
     */
    private $grrAreaRepository;

    public function __construct(GrrRoomRepository $grrRoomRepository, GrrAreaRepository $grrAreaRepository)
    {
        $this->grrRoomRepository = $grrRoomRepository;
        $this->grrAreaRepository = $grrAreaRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('startTime', TimestampFieldType::class)
            ->add('endTime', TimestampFieldType::class)
            ->add('entryType')
            ->add('repeatId')
            ->add(
                'area',
                EntityType::class,
                [
                    'required' => false,
                    'placeholder' => 'Area',
                    'class' => GrrArea::class,
                    'query_builder' => $this->grrAreaRepository->getQueryBuilder(),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                    'mapped' => false,
                ]
            )
            ->add(
                'roomId',
                EntityType::class,
                [
                    'required' => false,
                    'placeholder' => 'Room',
                    'class' => GrrRoom::class,
                    'query_builder' => $this->grrRoomRepository->getQueryBuilder(),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->add('timestamp')
            ->add('createBy')
            ->add('beneficiaireExt')
            ->add('beneficiaire')
            ->add('type', GrrEntryTypeField::class)
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
                'data_class' => GrrEntry::class,
            ]
        );
    }
}
