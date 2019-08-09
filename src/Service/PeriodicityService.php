<?php


namespace App\Service;


use App\Entity\Entry;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PeriodicityService
{
    /**
     * @var Entry
     */
    protected $entry;
    /**
     * @var \DateTimeInterface|null
     */
    private $endTime;

    /**
     * @param Entry $entry
     * @return CarbonPeriod|array
     * @throws \Exception
     */
    public function getDays(Entry $entry): CarbonPeriod
    {
        $periodicity = $entry->getPeriodicity();
        if (!$periodicity) {
            throw new \Exception('Pas de periodicity');
        }

        $this->entry = $entry;
        $this->endTime = $periodicity->getEndTime();

        if ($periodicity->getEveryDay()) {
            return $this->forEveryDays();
        }

        if ($periodicity->getEveryYear()) {
            return $this->forEveryYears();
        }

        if ($periodicity->getEveryMonthSameDay()) {
            return $this->forEveryMonthSameDay();
        }

        if ($periodicity->getEveryMonthSameWeekOfDay()) {
            return $this->forEveryMonthSameDayOfWeek();
        }

        if ($periodicity->getEveryWeek()) {
            return $this->forEveryWeek();
        }

        return [];
    }

    protected function forEveryDays(): CarbonPeriod
    {
        $start = Carbon::instance($this->entry->getStartTime());
        $end = Carbon::instance($this->endTime);

        return Carbon::parse($start->toDateString())->daysUntil(
            $end->toDateString()
        );
    }

    /**
     *
     * @return CarbonPeriod
     */
    protected function forEveryYears()
    {
        $start = Carbon::instance($this->entry->getStartTime());
        $end = Carbon::instance($this->endTime);

        $period = Carbon::parse($start->toDateString())->yearsUntil(
            $end->toDateString()
        );

        $weekendFilter = function ($date) use ($start) {
            return $date->day == $start->day;
        };

        $period->filter($weekendFilter);

        return $period;
    }

    /**
     * Par exemple 12-08 12-09 12-10
     * @return CarbonPeriod
     */
    protected function forEveryMonthSameDay()
    {
        $start = Carbon::instance($this->entry->getStartTime());
        $end = Carbon::instance($this->endTime);

        $period = Carbon::parse($start->toDateString())->daysUntil(
            $end->toDateString()
        );

        $weekendFilter = function ($date) use ($start) {
            return $date->day == $start->day;
        };

        $period->filter($weekendFilter);

        return $period;
    }

    /**
     * Par exemple le premier samedi de chaque mois
     * @return CarbonPeriod
     */
    private function forEveryMonthSameDayOfWeek()
    {
        $start = Carbon::instance($this->entry->getStartTime());
        $end = Carbon::instance($this->endTime);

        $period = Carbon::parse($start->toDateString())->daysUntil(
            $end->toDateString()
        );

        $weekendFilter = function ($date) use ($start) {
            return $date->dayOfWeek == $start->dayOfWeek && $date->weekOfMonth == $start->weekOfMonth;
        };

        $period->filter($weekendFilter);

        return $period;

    }

    protected function forEveryWeek()
    {
        $start = Carbon::instance($this->entry->getStartTime());
        $end = Carbon::instance($this->endTime);

        $period = Carbon::parse($start->toDateString())->daysUntil(
            $end->toDateString()
        );

        $weekendFilter = function ($date) use ($start) {
            return $date->day == $start->day;
        };

        $period->filter($weekendFilter);

        return $period;
    }



}