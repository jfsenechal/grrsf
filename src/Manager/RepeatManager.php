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
    private $grrEntryRepository;

    public function __construct(RepeatRepository $grrEntryRepository)
    {
        $this->grrEntryRepository = $grrEntryRepository;
    }

    public function persist(Repeat $grrEntry)
    {
        $this->grrEntryRepository->persist($grrEntry);
    }

    public function remove(Repeat $grrEntry)
    {
        $this->grrEntryRepository->remove($grrEntry);
    }

    public function flush()
    {
        $this->grrEntryRepository->flush();
    }

    public function insert(Repeat $grrEntry)
    {
        $this->grrEntryRepository->insert($grrEntry);
    }

}