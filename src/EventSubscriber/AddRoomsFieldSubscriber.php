<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Form\Type\RoomSelectType;
use App\Model\AuthorizationModel;
use App\Model\AuthorizationResourceModel;
use App\Repository\RoomRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddRoomsFieldSubscriber implements EventSubscriberInterface
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
         * @var AuthorizationModel
         */
        $entry = $event->getData();
        $area = $entry->getArea();
        $form = $event->getForm();

        $default = [
            'multiple' => true,
            'expanded' => true,
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
            'rooms',
            RoomSelectType::class,
            $default
        );
    }
}
