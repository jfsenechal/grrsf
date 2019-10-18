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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddRoomFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var bool
     */
    private $required;
    /**
     * @var string|null
     */
    private $label;
    /**
     * @var string|null
     */
    private $placeholder;

    public function __construct(
        bool $required = false,
        ?string $label = null,
        ?string $placeholder = null
    ) {
        $this->required = $required;
        $this->label = $label;
        $this->placeholder = $placeholder;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event): void
    {
        $object = $event->getData();

        if (is_array($object)) { // is_array = search form
            $area = null;
        } else {
            $area = $object->getArea();
        }

        $form = $event->getForm();

        $default = [
            'required' => $this->required,
        ];

        if ($area) {
            $default['query_builder'] = function (RoomRepository $roomRepository) use ($area): \Doctrine\ORM\QueryBuilder {
                return $roomRepository->getRoomsByAreaQueryBuilder($area);
            };
        } else {
            $default['choices'] = [];
        }

        if ($this->label) {
            $default['label'] = $this->label;
        }

        if ($this->placeholder) {
            $default['placeholder'] = $this->placeholder;
        }

        if (false === $this->required) {
            $default['placeholder'] = 'room.form.select.placeholder';
        }

        $form->add(
            'room',
            RoomSelectType::class,
            $default
        );
    }
}
