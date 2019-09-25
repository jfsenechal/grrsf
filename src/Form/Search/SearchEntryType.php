<?php

namespace App\Form\Search;

use App\Entity\EntryType;
use App\EventSubscriber\AddRoomFieldSubscriber;
use App\Form\Type\AreaSelectType;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchEntryType extends AbstractType
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                SearchType::class,
                [
                    'required' => false,
                    'label' => false,
                    'attr' => ['placeholder' => 'search.form.word.placeholder', 'class' => 'my-1 mr-sm-2'],
                ]
            )
            ->add(
                'entry_type',
                EntityType::class,
                [
                    'class' => EntryType::class,
                    'required' => false,
                    'label' => false,
                    'help'=>null,
                    'placeholder' => 'typeEntry.index.title',
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->add(
                'area',
                AreaSelectType::class,
                [
                    'required' => false,
                    'label' => false,
                    'placeholder' => 'area.form.select.placeholder',
                ]
            )
            ->addEventSubscriber(new AddRoomFieldSubscriber('room', false, false, false));

    }

}
