<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 18/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Periodicity;

use App\Entity\Entry;
use App\Manager\EntryManager;
use App\Manager\PeriodicityManager;
use App\Repository\EntryRepository;

class HandlerPeriodicity
{
    /**
     * @var PeriodicityManager
     */
    private $periodicityManager;
    /**
     * @var PeriodicityDaysProvider
     */
    private $periodicityDaysProvider;
    /**
     * @var EntryManager
     */
    private $entryManager;
    /**
     * @var GeneratorEntry
     */
    private $generatorEntry;
    /**
     * @var EntryRepository
     */
    private $entryRepository;

    public function __construct(
        PeriodicityManager $periodicityManager,
        PeriodicityDaysProvider $periodicityDaysProvider,
        EntryManager $entryManager,
        EntryRepository $entryRepository,
        GeneratorEntry $generatorEntry
    ) {
        $this->periodicityManager = $periodicityManager;
        $this->periodicityDaysProvider = $periodicityDaysProvider;
        $this->entryManager = $entryManager;
        $this->generatorEntry = $generatorEntry;
        $this->entryRepository = $entryRepository;
    }

    public function handleNewPeriodicity(Entry $entry)
    {
        $periodicity = $entry->getPeriodicity();
        if ($periodicity) {
            $days = $this->periodicityDaysProvider->getDaysByEntry($entry);
            foreach ($days as $day) {
                $newEntry = $this->generatorEntry->generateEntry($entry, $day);
                $this->entryManager->persist($newEntry);
            }
            $this->entryManager->flush();
        }
    }

    /**
     * @param Entry $oldEntry
     * @param Entry $entry
     *
     * @throws \Exception
     */
    public function handleEditPeriodicity(Entry $oldEntry, Entry $entry)
    {
        $periodicity = $entry->getPeriodicity();
        if (!$periodicity) {
            return null;
        }

        $type = $periodicity->getType();

        /*
         * Si la périodicité mise sur 'aucune'
         */
        if (0 === $type || null === $type) {
            $entry->setPeriodicity(null);
            $this->entryManager->removeEntriesByPeriodicity($periodicity, $entry);
            $this->periodicityManager->remove($periodicity);
            $this->periodicityManager->flush();

            return null;
        }

        /*
         * ici on supprime les entries de la periodicité mais on garde l'entry de base
         * et on reinjecte les nouvelles entries
         */
        $this->entryManager->removeEntriesByPeriodicity($periodicity, $entry);
        $days = $this->periodicityDaysProvider->getDaysByEntry($entry);
        foreach ($days as $day) {
            $newEntry = $this->generatorEntry->generateEntry($entry, $day);
            //  $this->entryRepository->findPeriodicityEntry($entry);
            $this->entryManager->persist($newEntry);
        }
        $this->entryManager->flush();

        return null;
    }

    public function periodicityHasChange(Entry $oldEntry, Entry $entry): bool
    {
        if ($oldEntry->getStartTime() !== $entry->getStartTime()) {
            return true;
        }

        if ($oldEntry->getEndTime() !== $entry->getEndTime()) {
            return true;
        }

        $oldPeriodicity = $oldEntry->getPeriodicity();
        $periodicity = $entry->getPeriodicity();

        if (null === $oldPeriodicity || null === $periodicity) {
            return true;
        }

        if ($oldPeriodicity->getEndTime() !== $periodicity->getEndTime()) {
            return true;
        }
        if ($oldPeriodicity->getType() !== $periodicity->getType()) {
            return true;
        }
        if ($oldPeriodicity->getWeekRepeat() !== $periodicity->getWeekRepeat()) {
            return true;
        }
        if ($oldPeriodicity->getWeekDays() !== $periodicity->getWeekDays()) {
            return true;
        }

        return false;
    }
}
