<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Entity\Room;
use App\Entity\Security\UserManagerResource;
use App\Model\UserManagerResourceModel;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddRoomFieldSubscriber implements EventSubscriberInterface
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
        /**
         * @var UserManagerResourceModel $entry
         */
        $entry = $event->getData();
        $area = $entry->getArea();
        $form = $event->getForm();
        $room = $entry->getRooms();

        if ($room) {
           // $form->add('room', HiddenType::class);

            return;
        }

        if ($area) {
            $form->add(
                'rooms',
                EntityType::class,
                [
                    'label' => 'entry.form.room.select.label',
                    'help' => 'entry.form.room.help',
                    'class' => Room::class,
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'placeholder' => 'entry.form.room.select.placeholder',
                    'query_builder' => $this->roomRepository->getRoomsByAreaQueryBuilder($area),
                    'attr' => ['class' => 'custom-control custom-checkbox my-1 mr-sm-2'],
                ]
            );

            return;
        }

        $form->add(
            'rooms',
            ChoiceType::class,
            [
                'choices' => [],
            ]
        );

    }
}
