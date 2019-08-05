<?php


namespace App\Service;


use App\Repository\AreaRepository;

class Settingservice
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    public function getDefaultArea()
    {
       $areas = $this->areaRepository->findAll();
       return $areas[0];
    }
}