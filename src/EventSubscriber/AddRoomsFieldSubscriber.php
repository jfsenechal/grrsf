<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddRoomsFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event)
    {
        $entry = $event->getData();
        $area = $entry->getArea();
        $form = $event->getForm();

        if (!$area) {
            return;
        }

        $form->add(
            'room',
            EntityType::class,
            [
                'label' => 'entry.form.room.label',
                'help' => 'entry.form.room.help',
                'required' => true,
                'class' => Room::class,
                'placeholder' => 'entry.form.room.select.placeholder',
                'query_builder' => $this->roomRepository->getRoomsByAreaQueryBuilder($area),
                'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
            ]
        );
    }
}
