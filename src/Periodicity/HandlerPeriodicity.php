<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 18/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Periodicity;

use App\Entity\Entry;
use App\Manager\EntryManager;
use App\Manager\PeriodicityManager;

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

    public function __construct(
        PeriodicityManager $periodicityManager,
        PeriodicityDaysProvider $periodicityDaysProvider,
        EntryManager $entryManager,
        GeneratorEntry $generatorEntry
    ) {
        $this->periodicityManager = $periodicityManager;
        $this->periodicityDaysProvider = $periodicityDaysProvider;
        $this->entryManager = $entryManager;
        $this->generatorEntry = $generatorEntry;
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
     * @return null
     * @throws \Exception
     */
    public function handleEditPeriodicity(Entry $oldEntry, Entry $entry)
    {
        $periodicity = $entry->getPeriodicity();
        $oldPeriodicity = $oldEntry->getPeriodicity();

        $type = $periodicity->getType();

        /**
         * Si la périodicité est supprimée
         */
        if ($type === 0 || $type === null) {
            $entry->setPeriodicity(null);
            //pas de flush() periodicity type est a null, error sql
            $this->entryManager->getEntityManager()->getUnitOfWork()->commit($entry);
            $this->entryManager->removeEntriesByPeriodicity($periodicity);
            $this->periodicityManager->remove($periodicity);
            $this->periodicityManager->flush();

            return null;
        }

        $entrySave = clone $entry;
        $this->entryManager->removeEntriesByPeriodicity($periodicity);
        $days = $this->periodicityDaysProvider->getDaysByEntry($entry);
        foreach ($days as $day) {
            $newEntry = $this->generatorEntry->generateEntry($entrySave, $day);
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

        if ($oldPeriodicity === null || $periodicity === null) {
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