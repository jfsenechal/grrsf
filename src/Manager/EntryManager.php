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
    private $entryRepository;

    public function __construct(EntryRepository $entryRepository)
    {
        $this->grrEntryRepository = $entryRepository;
    }

    public function persist(Entry $entry)
    {
        $this->grrEntryRepository->persist($entry);
    }

    public function remove(Entry $entry)
    {
        $this->grrEntryRepository->remove($entry);
    }

    public function flush()
    {
        $this->grrEntryRepository->flush();
    }

    public function insert(Entry $entry)
    {
        $this->grrEntryRepository->insert($entry);
    }

}