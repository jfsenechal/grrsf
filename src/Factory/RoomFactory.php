<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\Room;

class RoomFactory implements FactoryInterface
{
    public function createNew(): Room
    {
        return new Room();
    }

    public function setDefaultValues(Room $room)
    {
        $room
            ->setCapacity(0)
            ->setMaxBooking(-1)
            ->setStatutRoom(false)
            ->setShowFicRoom(false)
            ->setShowComment(false)
            ->setDelaisMaxResaRoom(-1)
            ->setDelaisMinResaRoom(0)
            ->setAllowActionInPast(false)
            ->setOrderDisplay(0)
            ->setDelaisOptionReservation(0)
            ->setDontAllowModify(false)
            ->setTypeAffichageReser(0)
            ->setModerate(0)
            ->setQuiPeutReserverPour(5)
            ->setActiveRessourceEmpruntee(0)
            ->setWhoCanSee(0);
    }
}