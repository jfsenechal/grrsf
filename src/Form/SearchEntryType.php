<?php

namespace App\Form;

use App\Entity\GrrRoom;
use App\GrrData\EntryData;
use App\Repository\GrrRoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchEntryType extends AbstractType
{
    /**
     * @var GrrRoomRepository
     */
    private $grrRoomRepository;
    /**
     * @var EntryData
     */
    private $entryData;

    public function __construct(GrrRoomRepository $grrRoomRepository, EntryData $entryData)
    {
        $this->grrRoomRepository = $grrRoomRepository;
        $this->entryData = $entryData;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                SearchType::class,
                [
                    'required' => false,
                    'attr' => ['placeholder' => 'Name', 'class' => 'my-1 mr-sm-2'],
                ]
            )
            ->add(
                'entry_type',
                ChoiceType::class,
                [
                    'required' => false,
                    'placeholder' => 'Entry type',
                    'choices' => $this->entryData->getEntryTypes(),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->add(
                'room',
                EntityType::class,
                [
                    'required' => false,
                    'placeholder' => 'Room',
                    'class' => GrrRoom::class,
                    'query_builder' => $this->grrRoomRepository->getQueryBuilder(),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->add(
                'type',
                ChoiceType::class,
                [
                    'required' => false,
                    'placeholder' => 'Type périodicité',
                    'choices' => array_flip($this->entryData->getTypesPeriodicite()),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

            ]
        );
    }
}
