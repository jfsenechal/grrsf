<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\Repeat;
use App\Repository\RepeatRepository;

class RepeatManager
{
    /**
     * @var RepeatRepository
     */
    private $entryRepository;

    public function __construct(RepeatRepository $entryRepository)
    {
        $this->grrEntryRepository = $entryRepository;
    }

    public function persist(Repeat $entry)
    {
        $this->grrEntryRepository->persist($entry);
    }

    public function remove(Repeat $entry)
    {
        $this->grrEntryRepository->remove($entry);
    }

    public function flush()
    {
        $this->grrEntryRepository->flush();
    }

    public function insert(Repeat $entry)
    {
        $this->grrEntryRepository->insert($entry);
    }

}