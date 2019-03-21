<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00
 */

namespace App\Events;


use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddRoomsFieldListener implements EventSubscriberInterface
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
        $area = $event->getData();
        $form = $event->getForm();
        dump($area);

        if ($area) {
            $form->add(
                'rooms',
                EntityType::class,
                [
                    'class' => Room::class,
                    'required' => false,
                    'placeholder' => 'menu.select.room',
                    'query_builder' => $this->roomRepository->getRoomsByAreaQueryBuilder($area),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            );
        } else {
            $form->add(
                'rooms',
                ChoiceType::class,
                [
                    'choices' => [],
                ]
            );

        }


        // checks whether the user from the initial data has chosen to
        // display their email or not.
        /*    if (true === $user->isShowEmail()) {
                $form->add('email', EmailType::class);
            }*/
    }


}