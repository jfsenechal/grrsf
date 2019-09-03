<?php

namespace App\Events;

use App\Entity\Room;
use Symfony\Contracts\EventDispatcher\Event;

class RoomEvent extends Event
{
    const ROOM_NEW_INITIALIZE = 'grr.room.new.initialize';
    const ROOM_NEW_SUCCESS = 'grr.room.new.success';
    const ROOM_NEW_COMPLETE = 'grr.room.new.complete';
    const ROOM_EDIT_SUCCESS = 'grr.room.edit.success';
    const ROOM_DELETE_SUCCESS = 'grr.room.delete.success';

    /**
     * @var Room
     */
    private $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }
}
