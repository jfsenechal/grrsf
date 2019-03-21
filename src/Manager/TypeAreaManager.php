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

    public function persist(TypeArea $area)
    {
        $this->typeAreaRepository->persist($area);
    }

    public function remove(TypeArea $area)
    {
        $this->typeAreaRepository->remove($area);
    }

    public function flush()
    {
        $this->typeAreaRepository->flush();
    }

    public function insert(TypeArea $area)
    {
        $this->typeAreaRepository->insert($area);
    }

}