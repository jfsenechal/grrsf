<?php

namespace App\Service;

use App\Entity\Area;
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

    public function getDefaultArea(): ?Area
    {
        return $this->areaRepository->findOneBy([], ['id' => 'ASC']);
    }
}
