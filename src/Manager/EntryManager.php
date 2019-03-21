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
        $this->entryRepository = $entryRepository;
    }

    public function persist(Entry $entry)
    {
        $this->entryRepository->persist($entry);
    }

    public function remove(Entry $entry)
    {
        $this->entryRepository->remove($entry);
    }

    public function flush()
    {
        $this->entryRepository->flush();
    }

    public function insert(Entry $entry)
    {
        $this->entryRepository->insert($entry);
    }

}