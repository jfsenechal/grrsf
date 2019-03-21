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
    private $grrSettingRepository;

    public function __construct(SettingRepository $grrSettingRepository)
    {
        $this->grrSettingRepository = $grrSettingRepository;
    }

    public function persist(Area $grrArea)
    {
        $this->grrSettingRepository->persist($grrArea);
    }

    public function remove(Area $grrArea)
    {
        $this->grrSettingRepository->remove($grrArea);
    }

    public function flush()
    {
        $this->grrSettingRepository->flush();
    }

    public function insert(Area $grrArea)
    {
        $this->grrSettingRepository->insert($grrArea);
    }

}