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
    private $grrAreaRepository;

    public function __construct(AreaRepository $grrAreaRepository)
    {
        $this->grrAreaRepository = $grrAreaRepository;
    }

    public function persist(Area $grrArea)
    {
        $this->grrAreaRepository->persist($grrArea);
    }

    public function remove(Area $grrArea)
    {
        $this->grrAreaRepository->remove($grrArea);
    }

    public function flush()
    {
        $this->grrAreaRepository->flush();
    }

    public function insert(Area $grrArea)
    {
        $this->grrAreaRepository->insert($grrArea);
    }

}