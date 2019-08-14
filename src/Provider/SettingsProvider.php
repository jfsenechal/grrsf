<?php

namespace App\Provider;

use App\Entity\Area;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;

class SettingsProvider
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(AreaRepository $areaRepository, RoomRepository $roomRepository)
    {
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * @return Area|null
     * @todo
     */
    public function getDefaultArea(): ?Area
    {
        return $this->areaRepository->findOneBy([], ['id' => 'ASC']);
    }

    /**
     * @return mixed
     * @todo
     */
    public function getDefaulRoom()
    {
        return $this->roomRepository->findOneBy([], ['id' => 'ASC']);
    }
}
