<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;


use App\Entity\Entry;
use App\Repository\EntryRepository;

class EntryManager
{
    /**
     * @var EntryRepository
     */
    private $grrEntryRepository;

    public function __construct(EntryRepository $grrEntryRepository)
    {
        $this->grrEntryRepository = $grrEntryRepository;
    }

    public function persist(Entry $grrEntry)
    {
        $this->grrEntryRepository->persist($grrEntry);
    }

    public function remove(Entry $grrEntry)
    {
        $this->grrEntryRepository->remove($grrEntry);
    }

    public function flush()
    {
        $this->grrEntryRepository->flush();
    }

    public function insert(Entry $grrEntry)
    {
        $this->grrEntryRepository->insert($grrEntry);
    }

}