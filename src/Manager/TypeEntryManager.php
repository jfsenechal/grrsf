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

class TypeEntryManager
{
    /**
     * @var EntryTypeRepository
     */
    private $entryTypeRepository;

    public function __construct(EntryTypeRepository $entryTypeRepository)
    {
        $this->entryTypeRepository = $entryTypeRepository;
    }

    public function persist(EntryType $area)
    {
        $this->entryTypeRepository->persist($area);
    }

    public function remove(EntryType $area)
    {
        $this->entryTypeRepository->remove($area);
    }

    public function flush()
    {
        $this->entryTypeRepository->flush();
    }

    public function insert(EntryType $area)
    {
        $this->entryTypeRepository->insert($area);
    }
}
