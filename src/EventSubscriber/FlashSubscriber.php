<?php

namespace App\EventSubscriber;

use App\Events\AreaEvent;
use App\Events\AuthorizationEvent;
use App\Events\EntryEvent;
use App\Events\EntryTypeAreaEvent;
use App\Events\EntryTypeEvent;
use App\Events\RoomEvent;
use App\Events\SettingSuccessEvent;
use App\Events\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
        $this->flashBag->add('success', 'typeEntry.flash.delete');
    }

    public function onEntryTypeEdit(EntryTypeEvent $entryTypeEvent)
    {
        $this->flashBag->add('success', 'typeEntry.flash.edit');
    }

    public function onEntryTypeNew(EntryTypeEvent $entryTypeEvent)
    {
        $this->flashBag->add('success', 'typeEntry.flash.new');
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

    public function onSettingSuccess()
    {
        $this->flashBag->add('success', 'setting.flash.edit');
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

    public function onAuthorizationDelete(AuthorizationEvent $event)
    {
        $this->flashBag->add('success', 'authorization.flash.delete.success');
    }

    public function onAuthorizationNew(AuthorizationEvent $event)
    {
        $this->flashBag->add('success', 'authorization.flash.new');
    }

    public function onEditEntryTypeArea()
    {
        $this->flashBag->add('success','entryType.area.flash');
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

            AuthorizationEvent::NEW_SUCCESS => 'onAuthorizationNew',
            AuthorizationEvent::DELETE_SUCCESS => 'onAuthorizationDelete',

            EntryTypeAreaEvent::class => 'onEditEntryTypeArea',
            SettingSuccessEvent::class => 'onSettingSuccess'
        ];

    }
}
