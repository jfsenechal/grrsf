<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

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

    public function removeEntriesByPeriodicity(?Periodicity $periodicity)
    {
        foreach ($this->entryRepository->findBy(['periodicity' => $periodicity]) as $entry) {
            $this->remove($entry);
        }
    }
}
