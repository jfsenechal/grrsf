<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 20:19
 */

namespace App\Booking;


use App\Entity\GrrEntry;
use App\Entity\GrrRepeat;
use App\Factory\RepeatFactory;
use App\Manager\GrrRepeatManager;
use App\Repository\GrrRepeatRepository;

class Repeat
{
    /**
     * @var GrrRepeatRepository
     */
    private $grrRepeatRepository;
    /**
     * @var GrrRepeatManager
     */
    private $grrRepeatManager;
    /**
     * @var RepeatFactory
     */
    private $repeatFactory;

    public function __construct(
        GrrRepeatRepository $grrRepeatRepository,
        GrrRepeatManager $grrRepeatManager,
        RepeatFactory $repeatFactory
    ) {
        $this->grrRepeatRepository = $grrRepeatRepository;
        $this->grrRepeatManager = $grrRepeatManager;
        $this->repeatFactory = $repeatFactory;
    }

    public function create(GrrEntry $grrEntry, \DateTime $date)
    {
        $repeat = $this->repeatFactory->createNew();
        $repeat->setName($grrEntry->getName());
        $repeat->setTimestamp($grrEntry->getTimestamp());
        $repeat->setBooking($grrEntry->getBooking());
        $repeat->setBooking($grrEntry->getBooking().$date->format('Y-m-d H:i:s'));
        $repeat->setRoomId($grrEntry->getRoomId());
        $repeat->setDescription($grrEntry->getDescription());
        $repeat->setBeneficiaire($grrEntry->getBeneficiaire());
        $repeat->setCreateBy($grrEntry->getCreateBy());
        $this->setDates($repeat, $date);
    }

    private function setDates(GrrRepeat $repeat, \DateTime $date)
    {
        $dateTimeDebut = $date;
        $dateTimeFin = clone($dateTimeDebut);
        $heure = $dateTimeDebut->format('H:i:s');
        if ($heure === '00:00:00') {
            //toute la journee de 8h a 23h
            $dateTimeDebut->setTime(8, 00);
            $dateTimeFin->setTime(23, 00);
        } else {
            $dateTimeFin->setTime(23, 00);
        }
        $repeat->setStartTime($dateTimeDebut->getTimestamp());
        $repeat->setEndTime($dateTimeFin->getTimestamp());
        $repeat->setEndTime();
    }

    private function setDefaultFields(GrrRepeat $repeat)
    {
        $repeat->setRepType();
        $repeat->setRepOpt();
        $repeat->setRepNumWeeks(null);
        $repeat->setBeneficiaireExt(' ');//pas null
        $repeat->setType('B');
        $repeat->setJours(0);
    }
}