<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\GrrArea;
use App\Factory\GrrAreaFactory;
use App\Repository\GrrRoomRepository;
use App\Repository\GrrSettingRepository;

class GrrSettingManager
{
    /**
     * @var GrrSettingRepository
     */
    private $grrSettingRepository;

    public function __construct(GrrSettingRepository $grrSettingRepository)
    {
        $this->grrSettingRepository = $grrSettingRepository;
    }

    public function persist(GrrArea $grrArea)
    {
        $this->grrSettingRepository->persist($grrArea);
    }

    public function remove(GrrArea $grrArea)
    {
        $this->grrSettingRepository->remove($grrArea);
    }

    public function flush()
    {
        $this->grrSettingRepository->flush();
    }

    public function insert(GrrArea $grrArea)
    {
        $this->grrSettingRepository->insert($grrArea);
    }

}