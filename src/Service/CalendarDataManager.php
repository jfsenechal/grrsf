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
use App\Model\Month;
use App\Model\Week;

class CalendarDataManager
{
    /**
     * @var Entry[] $entries
     */
    protected $entries;

    public function __construct()
    {
        $this->entries = [];
    }

    /**
     * Parcours tous les jours du mois
     * Crée une instance Day et set les entrées
     *
     * @param Month $param
     * @param Entry[] $entries
     * @throws \Exception
     */
    public function bindMonth(Month $month, array $entries)
    {
        $this->entries = $entries;
        foreach ($month->getCalendarDays() as $date) {
            $day = new Day($date);
            $events = $this->extractByDate($day);
            $day->addEntries($events);
            $month->addDataDay($day);
        }
    }

    /**
     * @param Week $week
     * @param Entry[] $entries
     * @throws \Exception
     */
    public function bindWeek(Week $week, array $entries)
    {
        $this->entries = $entries;
        foreach ($week->getCalendarDays() as $date) {
            $day = new Day($date);
            $events = $this->extractByDate($day);
            $day->addEntries($events);
            $week->addDataDay($day);
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