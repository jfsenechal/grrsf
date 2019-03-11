<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\GrrArea;
use App\Repository\GrrAreaRepository;

class GrrAreaManager
{
    /**
     * @var GrrAreaRepository
     */
    private $grrAreaRepository;

    public function __construct(GrrAreaRepository $grrAreaRepository)
    {
        $this->grrAreaRepository = $grrAreaRepository;
    }

    public function persist(GrrArea $grrArea)
    {
        $this->grrAreaRepository->persist($grrArea);
    }

    public function remove(GrrArea $grrArea)
    {
        $this->grrAreaRepository->remove($grrArea);
    }

    public function flush()
    {
        $this->grrAreaRepository->flush();
    }

    public function insert(GrrArea $grrArea)
    {
        $this->grrAreaRepository->insert($grrArea);
    }

}