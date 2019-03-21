<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\TypeArea;
use App\Repository\TypeAreaRepository;

class TypeAreaManager
{
    /**
     * @var TypeAreaRepository
     */
    private $typeAreaRepository;

    public function __construct(TypeAreaRepository $typeAreaRepository)
    {
        $this->typeAreaRepository = $typeAreaRepository;
    }

    public function persist(TypeArea $grrArea)
    {
        $this->typeAreaRepository->persist($grrArea);
    }

    public function remove(TypeArea $grrArea)
    {
        $this->typeAreaRepository->remove($grrArea);
    }

    public function flush()
    {
        $this->typeAreaRepository->flush();
    }

    public function insert(TypeArea $grrArea)
    {
        $this->typeAreaRepository->insert($grrArea);
    }

}