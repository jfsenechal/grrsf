<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Entity\Security\User;
use App\Form\Type\RoomSelectType;
use App\Repository\RoomRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddRoomDefaultFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event)
    {
        /**
         * @var User
         */
        $user = $event->getData();
        $area = $user->getAreaDefault();
        $form = $event->getForm();

        $default = [
            'required' => false,
            'label' => 'user.form.room.label',
            'attr' => ['class' => 'custom-select my-1 mr-sm-2 room-select'],
        ];

        if ($area) {
            $default['query_builder'] = function (RoomRepository $roomRepository) use ($area) {
                return $roomRepository->getRoomsByAreaQueryBuilder($area);
            };
        } else {
            $default['choices'] = [];
            $default['placeholder'] = 'room.form.select.empty.placeholder';
        }

        $form->add(
            'room_default',
            RoomSelectType::class,
            $default
        );
    }
}
