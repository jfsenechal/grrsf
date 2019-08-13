<?php

namespace App\Provider;

use App\Entity\Entry;
use App\Entity\Periodicity;
use App\GrrData\PeriodicityConstant;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class PeriodicityDaysProvider
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
     *
     * @return CarbonPeriod|array
     *
     * @throws \Exception
     */
    public function getDays(Entry $entry)
    {
        $periodicity = $entry->getPeriodicity();

        if ($periodicity === null) {
            return [];
        }

        $typePeriodicity = $periodicity->getType();

        $this->entry = $entry;

        $this->entry_start = Carbon::instance($this->entry->getStartTime());
        $this->periodicity_end = Carbon::instance($periodicity->getEndTime());

        if ($typePeriodicity === PeriodicityConstant::EVERY_DAY) {
            return $this->forEveryDays();
        }

        if ($typePeriodicity === PeriodicityConstant::EVERY_YEAR) {
            return $this->forEveryYears();
        }

        if ($typePeriodicity === PeriodicityConstant::EVERY_MONTH_SAME_DAY) {
            return $this->forEveryMonthSameDay();
        }

        if ($typePeriodicity === PeriodicityConstant::EVERY_MONTH_SAME_WEEK_DAY) {
            return $this->forEveryMonthSameDayOfWeek();
        }

        if ($typePeriodicity === PeriodicityConstant::EVERY_WEEK) {
            return $this->forEveryWeek($periodicity);
        }

        return [];
    }

    protected function forEveryDays(): CarbonPeriod
    {
        $period = CarbonPeriod::create(
            $this->entry_start->toDateString(),
            $this->periodicity_end->toDateString(),
            CarbonPeriod::EXCLUDE_START_DATE
        );

        return $this->applyFilter($period);
    }

    /**
     * @return CarbonPeriod
     */
    protected function forEveryYears(): CarbonPeriod
    {
        $period = Carbon::parse($this->entry_start->toDateString())->yearsUntil(
            $this->periodicity_end->toDateString()
        );

        return $this->applyFilter($period);
    }

    /**
     * Par exemple 12-08, 12-09 12-10, 12-11...
     *
     * @return CarbonPeriod
     */
    protected function forEveryMonthSameDay(): CarbonPeriod
    {
        $period = Carbon::parse($this->entry_start->toDateString())->daysUntil(
            $this->periodicity_end->toDateString()
        );

        $filter = function ($date) {
            return $date->day === $this->entry_start->day;
        };

        return $this->applyFilter($period, $filter);
    }

    /**
     * Par exemple le premier samedi de chaque mois.
     *
     * @return CarbonPeriod
     */
    private function forEveryMonthSameDayOfWeek(): CarbonPeriod
    {
        $period = Carbon::parse($this->entry_start->toDateString())->daysUntil(
            $this->periodicity_end->toDateString()
        );

        $filter = function ($date) {
            return $date->dayOfWeek === $this->entry_start->dayOfWeek && $date->weekOfMonth === $this->entry_start->weekOfMonth;
        };

        return $this->applyFilter($period, $filter);
    }

    /**
     * toutes les 1,2,3,4,5 semaines
     * lundi, mardi, mercredi...
     * @return CarbonPeriod
     */
    protected function forEveryWeek(Periodicity $periodicity): CarbonPeriod
    {
        $days = $periodicity->getDays();
        $repeat_week = $periodicity->getRepeatWeek();

        $filterDayOfWeek = function ($date) use ($days) {
            return in_array($date->dayOfWeekIso, $days, true);
        };

        $period = Carbon::parse($this->entry_start->toDateString())->daysUntil(
            $this->periodicity_end->toDateString()
        );

        $filterWeek = function ($date) use($repeat_week) {
            return $date->weekOfYear % $repeat_week === 0;
        };

        $period->excludeStartDate();
        $period->addFilter($filterDayOfWeek);

        if ($repeat_week > 1) {
            $period->addFilter($filterWeek);
        }

        return $period;
    }

    protected function applyFilter(CarbonPeriod $period, callable $filter = null): CarbonPeriod
    {
        $period->excludeStartDate();

        if ($filter) {
            $period->addFilter($filter);
        }

        return $period;
    }
}
