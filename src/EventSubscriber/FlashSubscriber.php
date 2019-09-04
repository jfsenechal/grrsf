<?php

namespace App\EventSubscriber;

use App\Events\AreaEvent;
use App\Events\AuthorizationModelEvent;
use App\Events\AuthorizationUserEvent;
use App\Events\EntryTypeEvent;
use App\Events\RoomEvent;
use App\Events\UserEvent;
use App\Model\AuthorizationModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events\EntryEvent;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FlashSubscriber implements EventSubscriberInterface
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function onEntryTypeDelete(EntryTypeEvent $entryTypeEvent)
    {
        $this->flashBag->add('success', 'entryType.flash.delete');
    }

    public function onEntryTypeEdit(EntryTypeEvent $entryTypeEvent)
    {
        $this->flashBag->add('success', 'entryType.flash.edit');
    }

    public function onEntryTypeNew(EntryTypeEvent $entryTypeEvent)
    {
        $this->flashBag->add('success', 'entryType.flash.new');
    }

    public function onRoomDelete(RoomEvent $roomEvent)
    {
        $this->flashBag->add('success', 'room.flash.delete');
    }

    public function onRoomEdit(RoomEvent $roomEvent)
    {
        $this->flashBag->add('success', 'room.flash.edit');
    }

    public function onRoomNew(RoomEvent $roomEvent)
    {
        $this->flashBag->add('success', 'room.flash.new');
    }

    public function onUserDelete(UserEvent $userEvent)
    {
        $this->flashBag->add('success', 'user.flash.delete');
    }

    public function onUserEdit(UserEvent $userEvent)
    {
        $this->flashBag->add('success', 'user.flash.edit');
    }

    public function onUserNew(UserEvent $userEvent)
    {
        $this->flashBag->add('success', 'user.flash.new');
    }

    public function onEntryNew(EntryEvent $event)
    {
        $this->flashBag->add('success', 'entry.flash.new');
    }

    public function onEntryEdit(EntryEvent $event)
    {
        $this->flashBag->add('success', 'entry.flash.edit');
    }

    public function onEntryDelete(EntryEvent $event)
    {
        $this->flashBag->add('success', 'entry.flash.delete');
    }

    public function onAreaDelete(AreaEvent $areaEvent)
    {
        $this->flashBag->add('success', 'area.flash.delete');
    }

    public function onAreaEdit(AreaEvent $areaEvent)
    {
        $this->flashBag->add('success', 'area.flash.edit');
    }

    public function onAreaNew(AreaEvent $areaEvent)
    {
        $this->flashBag->add('success', 'area.flash.new');
    }

    public function onAuthorizationModelDelete(AuthorizationModelEvent $event)
    {
        $this->flashBag->add('success', 'authorization.flash.model.delete');
    }

     public function onAuthorizationUserDelete(AuthorizationUserEvent $event)
    {
        $this->flashBag->add('success', 'authorization.flash.user.delete');
    }

    public function onAuthorizationUserNew(AuthorizationUserEvent $event)
    {
        $this->flashBag->add('success', 'authorization.flash.user.new');
    }

    public function onUserPassword(UserEvent $userEvent)
    {
        $this->flashBag->add('success', 'user.flash.password');
    }

    public static function getSubscribedEvents()
    {
        return [
            EntryEvent::NEW_SUCCESS => 'onEntryNew',
            EntryEvent::EDIT_SUCCESS => 'onEntryEdit',
            EntryEvent::DELETE_SUCCESS => 'onEntryDelete',

            AreaEvent::NEW_SUCCESS => 'onAreaNew',
            AreaEvent::EDIT_SUCCESS => 'onAreaEdit',
            AreaEvent::DELETE_SUCCESS => 'onAreaDelete',

            RoomEvent::NEW_SUCCESS => 'onRoomNew',
            RoomEvent::EDIT_SUCCESS => 'onRoomEdit',
            RoomEvent::DELETE_SUCCESS => 'onRoomDelete',

            EntryTypeEvent::NEW_SUCCESS => 'onEntryTypeNew',
            EntryTypeEvent::EDIT_SUCCESS => 'onEntryTypeEdit',
            EntryTypeEvent::DELETE_SUCCESS => 'onEntryTypeDelete',

            UserEvent::NEW_SUCCESS => 'onUserNew',
            UserEvent::EDIT_SUCCESS => 'onUserEdit',
            UserEvent::DELETE_SUCCESS => 'onUserDelete',
            UserEvent::CHANGE_PASSWORD_SUCCESS => 'onUserPassword',

            AuthorizationModelEvent::DELETE_SUCCESS => 'onAuthorizationModelDelete',

            AuthorizationUserEvent::NEW_SUCCESS => 'onAuthorizationUserNew',
            AuthorizationUserEvent::DELETE_SUCCESS => 'onAuthorizationUserDelete',
        ];

    }
}
