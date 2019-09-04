<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Form\Type\RoomSelectType;
use App\Repository\RoomRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddRoomFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $name_field;
    /**
     * @var bool
     */
    private $multiple;
    /**
     * @var bool
     */
    private $expanded;
    /**
     * @var bool
     */
    private $required;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $placeholder;

    public function __construct(
        string $name_field = 'room',
        bool $required = false,
        bool $multiple = false,
        bool $expanded = false,
        string $label = null,
        string $placeholder = null
    ) {
        $this->name_field = $name_field;
        $this->multiple = $multiple;
        $this->expanded = $expanded;
        $this->required = $required;
        $this->label = $label;
        $this->placeholder = $placeholder;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
            FormEvents::POST_SET_DATA => 'onPostSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    //no
    public function onPreSubmit(PreSubmitEvent $event)
    {
        dump(3);
        $sport = $event->getForm()->getData();
        dump($sport);
    }

    public function onSubmit(SubmitEvent $event)
    {
        dump(4);
        $sport = $event->getForm()->getData();
        dump($sport);
    }

    //init
    public function onPostSetData(FormEvent $event)
    {
        dump(2);
        $sport = $event->getForm()->getData();
        dump($sport);
    }

    public function onPostSubmit(PostSubmitEvent $event)
    {
        dump(5);
        $sport = $event->getForm()->getData();
        dump($sport);
    }


    //init
    public function onPreSetData(FormEvent $event)
    {
        dump(1);
        $object = $event->getData();

        if (is_array($object)) { // is_array = search form
            $area = null;
        } else {
            $area = $object->getArea();
        }

        $form = $event->getForm();

        $default = [
            'required' => $this->required,
            'multiple' => $this->multiple,
            'expanded' => $this->expanded,
        ];

        if ($area) {
            $default['query_builder'] = function (RoomRepository $roomRepository) use ($area) {
                return $roomRepository->getRoomsByAreaQueryBuilder($area);
            };
        } else {
            $default['choices'] = [];
        }

        if (false === $this->required) {
            $default['placeholder'] = 'room.form.select.placeholder';
        }

        if (true === $this->multiple) {
            $default['attr'] = ['class' => 'custom-control custom-checkbox my-1 mr-sm-2 room-select'];
        }

        if ($this->label) {
            $default['label'] = $this->label;
        }

        if ($this->placeholder) {
            $default['placeholder'] = $this->placeholder;
        }

        $form->add(
            $this->name_field,
            RoomSelectType::class,
            $default
        );

    }

}
