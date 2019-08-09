<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\EntryType;
use App\Repository\EntryTypeRepository;

class TypeAreaManager
{
    /**
     * @var EntryTypeRepository
     */
    private $typeAreaRepository;

    public function __construct(EntryTypeRepository $typeAreaRepository)
    {
        $this->typeAreaRepository = $typeAreaRepository;
    }

    public function persist(EntryType $area)
    {
        $this->typeAreaRepository->persist($area);
    }

    public function remove(EntryType $area)
    {
        $this->typeAreaRepository->remove($area);
    }

    public function flush()
    {
        $this->typeAreaRepository->flush();
    }

    public function insert(EntryType $area)
    {
        $this->typeAreaRepository->insert($area);
    }
}
