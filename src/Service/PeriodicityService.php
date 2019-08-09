<?php


namespace App\Service;

use App\Entity\Entry;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class PeriodicityService
{
    /**
     * @var Entry
     */
    protected $entry;
    /**
     * @var CarbonInterface
     */
    private $periodicity_end;
    /**
     * @var CarbonInterface
     */
    private $entry_start;

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

        $this->entry_start = Carbon::instance($this->entry->getStartTime());
        $this->periodicity_end = Carbon::instance($periodicity->getEndTime());

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
        return Carbon::parse($this->entry_start->toDateString())->daysUntil(
            $this->periodicity_end->toDateString()
        );
    }

    /**
     *
     * @return CarbonPeriod
     */
    protected function forEveryYears(): CarbonPeriod
    {
        $period = Carbon::parse($this->entry_start->toDateString())->yearsUntil(
            $this->periodicity_end->toDateString()
        );

        $filter = function ($date) {
            return $date->day == $this->entry_start->day;
        };

        return $this->applyFilter($period, $filter);
    }

    /**
     * Par exemple 12-08 12-09 12-10
     * @return CarbonPeriod
     */
    protected function forEveryMonthSameDay(): CarbonPeriod
    {
        $period = Carbon::parse($this->entry_start->toDateString())->daysUntil(
            $this->periodicity_end->toDateString()
        );

        $filter = function ($date) {
            return $date->day == $this->entry_start->day;
        };

        return $this->applyFilter($period, $filter);
    }

    /**
     * Par exemple le premier samedi de chaque mois
     * @return CarbonPeriod
     */
    private function forEveryMonthSameDayOfWeek(): CarbonPeriod
    {
        $period = Carbon::parse($this->entry_start->toDateString())->daysUntil(
            $this->periodicity_end->toDateString()
        );

        $filter = function ($date) {
            return $date->dayOfWeek == $this->entry_start->dayOfWeek && $date->weekOfMonth == $this->entry_start->weekOfMonth;
        };

        return $this->applyFilter($period, $filter);

    }

    protected function forEveryWeek(): CarbonPeriod
    {
        $period = Carbon::parse($this->entry_start->toDateString())->daysUntil(
            $this->periodicity_end->toDateString()
        );

        $filter = function ($date) {
            return $date->day == $this->entry_start->day;
        };

        return $this->applyFilter($period, $filter);
    }

    protected function applyFilter(CarbonPeriod $period, callable $filter): CarbonPeriod
    {
        $period::filter($filter);

        return $period;
    }


}