<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Entry;
use App\EventSubscriber\AddRoomsFieldSubscriber;
use App\Form\Type\EntryTypeField;
use App\Repository\AreaRepository;
use App\Repository\EntryTypeRepository;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
    /**
     * @var EntryTypeRepository
     */
    private $typeAreaRepository;

    public function __construct(
        RoomRepository $roomRepository,
        AreaRepository $areaRepository,
        EntryTypeRepository $typeAreaRepository
    ) {
        $this->roomRepository = $roomRepository;
        $this->areaRepository = $areaRepository;
        $this->typeAreaRepository = $typeAreaRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startTime',
                DateTimeType::class,
                [
                    'label' => 'entry.form.startTime.label',
                    'help' => 'entry.form.startTime.help',
                ]
            )
            ->add(
                'endTime',
                DateTimeType::class,
                [
                    'label' => 'entry.form.endTime.label',
                    'help' => 'entry.form.endTime.help',
                ]
            )
            /*      ->add(
                      'duration',
                      DurationTimeTypeField::class,
                      [
                          'label' => false,
                      ]
                  )*/
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'entry.form.name.label',
                    'help' => 'entry.form.name.help',
                ]
            )
            ->add(
                'area',
                EntityType::class,
                [
                    'label' => 'entry.form.area.label',
                    'help' => 'entry.form.area.help',
                    'required' => true,
                    'placeholder' => 'entry.form.area.label',
                    'class' => Area::class,
                    'query_builder' => $this->areaRepository->getQueryBuilder(),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                    // 'mapped' => false,
                ]
            )
            ->add(
                'type',
                EntryTypeField::class,
                [
                    'label' => 'entry.form.type.label',
                    'help' => 'entry.form.type.help',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'entry.form.description.label',
                    'help' => 'entry.form.description.help',
                ]
            )
            ->addEventSubscriber(new AddRoomsFieldSubscriber($this->roomRepository));
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
