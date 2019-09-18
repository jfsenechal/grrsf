<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 18/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Handler;


use App\Entity\Entry;
use App\Manager\EntryManager;
use App\Manager\PeriodicityManager;
use App\Periodicity\GeneratorEntry;
use App\Periodicity\PeriodicityDaysProvider;

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

    public function handleNewEntry(Entry $entry)
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

}