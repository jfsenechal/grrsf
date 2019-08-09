<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\Area;
use App\Repository\SettingRepository;

class SettingManager
{
    /**
     * @var SettingRepository
     */
    private $SettingRepository;

    public function __construct(SettingRepository $SettingRepository)
    {
        $this->SettingRepository = $SettingRepository;
    }

    public function persist(Area $area)
    {
        $this->SettingRepository->persist($area);
    }

    public function remove(Area $area)
    {
        $this->SettingRepository->remove($area);
    }

    public function flush()
    {
        $this->SettingRepository->flush();
    }

    public function insert(Area $area)
    {
        $this->SettingRepository->insert($area);
    }
}
