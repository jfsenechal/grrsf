<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\Area;
use App\Entity\PeriodicityDay;
use App\Repository\PeriodicityDayRepository;

class PeriodicityDayManager
{
    /**
     * @var PeriodicityDayRepository
     */
    private $periodicityDayRepository;

    public function __construct(PeriodicityDayRepository $periodicityDayRepository)
    {
        $this->periodicityDayRepository = $periodicityDayRepository;
    }

    public function persist(PeriodicityDay $periodicityDay)
    {
        $this->periodicityDayRepository->persist($periodicityDay);
    }

    public function remove(PeriodicityDay $periodicityDay)
    {
        $this->periodicityDayRepository->remove($periodicityDay);
    }

    public function flush()
    {
        $this->periodicityDayRepository->flush();
    }

    public function insert(Area $area)
    {
        $this->periodicityDayRepository->insert($area);
    }
}
