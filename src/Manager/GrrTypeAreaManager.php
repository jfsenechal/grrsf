<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\GrrTypeArea;
use App\Repository\GrrTypeAreaRepository;

class GrrTypeAreaManager
{
    /**
     * @var GrrTypeAreaRepository
     */
    private $typeAreaRepository;

    public function __construct(GrrTypeAreaRepository $typeAreaRepository)
    {
        $this->typeAreaRepository = $typeAreaRepository;
    }

    public function persist(GrrTypeArea $grrArea)
    {
        $this->typeAreaRepository->persist($grrArea);
    }

    public function remove(GrrTypeArea $grrArea)
    {
        $this->typeAreaRepository->remove($grrArea);
    }

    public function flush()
    {
        $this->typeAreaRepository->flush();
    }

    public function insert(GrrTypeArea $grrArea)
    {
        $this->typeAreaRepository->insert($grrArea);
    }

}