<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Entity\Entry;
use App\Form\Type\EntryTypeSelectField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddTypeEntryFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    public function onPreSetData(FormEvent $event)
    {
        /**
         * @var Entry $entry
         */
        $entry = $event->getData();
        $form = $event->getForm();
        $area = $entry->getArea();

        if ($area->getEntryTypes()->count() > 0) {
            $form->add(
                'type',
                EntryTypeSelectField::class,
                [
                    'choices' => $area->getEntryTypes(),
                ]
            );
        } else {
            $form->add(
                'type',
                EntryTypeSelectField::class,
                [

                ]
            );
        }
    }

    public function onPreSubmit(FormEvent $event)
    {
        $entry = $event->getData();
        $form = $event->getForm();

        if (!$entry) {
            return;
        }


    }

}
