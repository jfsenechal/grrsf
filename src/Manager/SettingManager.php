<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\Area;
use App\Factory\AreaFactory;
use App\Repository\RoomRepository;
use App\Repository\SettingRepository;

class SettingManager
{
    /**
     * @var SettingRepository
     */
    private $SettingRepository;

    public function __construct(SettingRepository $SettingRepository)
    {
        $this->grrSettingRepository = $SettingRepository;
    }

    public function persist(Area $area)
    {
        $this->grrSettingRepository->persist($area);
    }

    public function remove(Area $area)
    {
        $this->grrSettingRepository->remove($area);
    }

    public function flush()
    {
        $this->grrSettingRepository->flush();
    }

    public function insert(Area $area)
    {
        $this->grrSettingRepository->insert($area);
    }

}