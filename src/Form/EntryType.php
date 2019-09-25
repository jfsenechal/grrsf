<?php

namespace App\Form;

use AcMarche\GrhBundle\Repository\TypeContratRepository;
use App\Entity\Entry;
use App\EventSubscriber\AddDurationFieldSubscriber;
use App\EventSubscriber\AddRoomFieldSubscriber;
use App\EventSubscriber\AddTypeEntryFieldSubscriber;
use App\Factory\DurationFactory;
use App\Form\Type\AreaSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{
    /**
     * @var DurationFactory
     */
    private $durationFactory;

    public function __construct(DurationFactory $durationFactory)
    {
        $this->durationFactory = $durationFactory;
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
                'name',
                TextType::class,
                [
                    'label' => 'entry.form.name.label',
                    'help' => 'entry.form.name.help',
                ]
            )
            ->add(
                'area',
                AreaSelectType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'entry.form.description.label',
                    'help' => 'entry.form.description.help',
                    'required' => false,
                ]
            )
            ->addEventSubscriber(new AddTypeEntryFieldSubscriber())
            ->addEventSubscriber(new AddDurationFieldSubscriber($this->durationFactory))
            ->addEventSubscriber(new AddRoomFieldSubscriber('room', true, false, false));


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
