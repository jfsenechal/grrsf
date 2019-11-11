<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\Entry;
use App\Entity\Periodicity;
use App\Repository\EntryRepository;
use Doctrine\ORM\EntityManagerInterface;

class EntryManager extends BaseManager
{
    /**
     * @var EntryRepository
     */
    private $entryRepository;

    public function __construct(EntityManagerInterface $entityManager, EntryRepository $entryRepository)
    {
        parent::__construct($entityManager);
        $this->entryRepository = $entryRepository;
    }

    public function removeEntriesByPeriodicity(Periodicity $periodicity, Entry $entryToSkip): void
    {
        foreach ($this->entryRepository->findByPeriodicity($periodicity) as $entry) {
            if ($entry->getId() !== $entryToSkip->getId()) {
                $this->remove($entry);
            }
        }
    }
}
