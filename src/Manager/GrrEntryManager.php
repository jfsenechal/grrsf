<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;


use App\Entity\GrrEntry;
use App\Repository\GrrEntryRepository;

class GrrEntryManager
{
    /**
     * @var GrrEntryRepository
     */
    private $grrEntryRepository;

    public function __construct(GrrEntryRepository $grrEntryRepository)
    {
        $this->grrEntryRepository = $grrEntryRepository;
    }

    public function persist(GrrEntry $grrEntry)
    {
        $this->grrEntryRepository->persist($grrEntry);
    }

    public function remove(GrrEntry $grrEntry)
    {
        $this->grrEntryRepository->remove($grrEntry);
    }

    public function flush()
    {
        $this->grrEntryRepository->flush();
    }

    public function insert(GrrEntry $grrEntry)
    {
        $this->grrEntryRepository->insert($grrEntry);
    }

}