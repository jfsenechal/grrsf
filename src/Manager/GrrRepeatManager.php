<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\GrrRepeat;
use App\Repository\GrrRepeatRepository;

class GrrRepeatManager
{
    /**
     * @var GrrRepeatRepository
     */
    private $grrEntryRepository;

    public function __construct(GrrRepeatRepository $grrEntryRepository)
    {
        $this->grrEntryRepository = $grrEntryRepository;
    }

    public function persist(GrrRepeat $grrEntry)
    {
        $this->grrEntryRepository->persist($grrEntry);
    }

    public function remove(GrrRepeat $grrEntry)
    {
        $this->grrEntryRepository->remove($grrEntry);
    }

    public function flush()
    {
        $this->grrEntryRepository->flush();
    }

    public function insert(GrrRepeat $grrEntry)
    {
        $this->grrEntryRepository->insert($grrEntry);
    }

}