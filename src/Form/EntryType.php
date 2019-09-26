<?php

namespace App\Form;

use App\Entity\Entry;
use App\EventSubscriber\AddAreaFieldSubscriber;
use App\EventSubscriber\AddDurationFieldSubscriber;
use App\EventSubscriber\AddRoomFieldSubscriber;
use App\EventSubscriber\AddTypeEntryFieldSubscriber;
use App\Factory\DurationFactory;
use App\Security\AuthorizationHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class EntryType extends AbstractType
{
    /**
     * @var DurationFactory
     */
    private $durationFactory;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var AuthorizationHelper
     */
    private $authorizationHelper;

    public function __construct(
        DurationFactory $durationFactory,
        Security $security,
        AuthorizationHelper $authorizationHelper
    ) {
        $this->durationFactory = $durationFactory;
        $this->security = $security;
        $this->authorizationHelper = $authorizationHelper;
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
                'description',
                TextareaType::class,
                [
                    'label' => 'entry.form.description.label',
                    'help' => 'entry.form.description.help',
                    'required' => false,
                ]
            )
            ->addEventSubscriber(new AddAreaFieldSubscriber($this->security, $this->authorizationHelper))
            ->addEventSubscriber(new AddTypeEntryFieldSubscriber())
            ->addEventSubscriber(new AddDurationFieldSubscriber($this->durationFactory))
            ->addEventSubscriber(new AddRoomFieldSubscriber( true ));
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
