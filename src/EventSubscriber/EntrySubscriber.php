<?php

namespace App\EventSubscriber;

use App\Events\EntryEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EntrySubscriber implements EventSubscriberInterface
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function onCreated(EntryEvent $event)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            EntryEvent::ENTRY_NEW_INITIALIZE => 'onCreated',
        ];
    }
}
