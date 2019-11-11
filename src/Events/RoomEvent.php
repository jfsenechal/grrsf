<?php

namespace App\Events;

use App\Entity\Room;
use Symfony\Contracts\EventDispatcher\Event;

class RoomEvent extends Event
{
    const NEW_SUCCESS = 'grr.room.new.success';
    const EDIT_SUCCESS = 'grr.room.edit.success';
    const DELETE_SUCCESS = 'grr.room.delete.success';

    /**
     * @var Room
     */
    private $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }
}
