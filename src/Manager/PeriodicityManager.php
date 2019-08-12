<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\Periodicity;
use App\Repository\PeriodicityRepository;

class PeriodicityManager
{
    /**
     * @var PeriodicityRepository
     */
    private $periodicityRepository;

    public function __construct(PeriodicityRepository $periodicityRepository)
    {
        $this->periodicityRepository = $periodicityRepository;
    }

    public function persist(Periodicity $periodicity)
    {
        $this->periodicityRepository->persist($periodicity);
    }

    public function remove(Periodicity $periodicity)
    {
        $this->periodicityRepository->remove($periodicity);
    }

    public function flush()
    {
        $this->periodicityRepository->flush();
    }

    public function insert(Periodicity $periodicity)
    {
        $this->periodicityRepository->insert($periodicity);
    }
}
