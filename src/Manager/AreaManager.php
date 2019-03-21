<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\Area;
use App\Repository\AreaRepository;

class AreaManager
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    public function persist(Area $area)
    {
        $this->areaRepository->persist($area);
    }

    public function remove(Area $area)
    {
        $this->areaRepository->remove($area);
    }

    public function flush()
    {
        $this->areaRepository->flush();
    }

    public function insert(Area $area)
    {
        $this->areaRepository->insert($area);
    }

}