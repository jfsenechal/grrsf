<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 21/03/19
 * Time: 11:35
 */

namespace App\Service;

use App\Entity\Entry;
use App\Model\Day;

class CalendarDataManager
{
    /**
     * @var Entry[] $entries
     */
    protected $entries;

    /**
     * @return Entry[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param Entry[] $entries
     */
    public function setEntries(array $entries): void
    {
        $this->entries = $entries;
    }

    public function add(Day $day)
    {
        $entries = $this->extractByDate($day->getDate());
        if (count($entries) > 0) {
            $day->setEntries($entries);
        }

    }

    public function extractByDate(\DateTimeInterface $dateTime)
    {
        $data = [];
        foreach ($this->entries as $entry) {
            if ($entry->getStartTime()->format('Y-m-d') == $dateTime->format('Y-m-d')) {
                $data[] = $entry;
            }
        }

        return $data;
    }
}