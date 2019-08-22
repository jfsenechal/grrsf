<?php

namespace App\Provider;

use App\Entity\Entry;
use App\Entity\Periodicity;
use App\GrrData\PeriodicityConstant;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;

class PeriodicityDaysProvider
{
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
     * @return array|CarbonPeriod
     */
    public function getDaysByEntry(Entry $entry)
    {
        $periodicity = $entry->getPeriodicity();

        if ($periodicity === null) {
            return [];
        }

        return $this->getDaysByPeriodicity($periodicity, $entry->getStartTime());
    }

    /**
     * @param Periodicity $periodicity
     * @param \DateTimeInterface $startTime
     * @return array|CarbonPeriod
     */
    public function getDaysByPeriodicity(
        Periodicity $periodicity,
        \DateTimeInterface $startTime
    ) {
        $typePeriodicity = $periodicity->getType();

        $this->entry_start = Carbon::instance($startTime);
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
        /**
         * monday, tuesday, wednesday
         * @example : [1,2,3]
         */
        $days = $periodicity->getWeekDays();
        /**
         * @example 1 for every weeks, 2 every 2 weeks, 3,4...
         */
        $repeat_week = $periodicity->getWeekRepeat();

        /**
         * filter days of the week
         * @param $date
         * @return bool
         */
        $filterDayOfWeek = function ($date) use ($days) {
            return in_array($date->dayOfWeekIso, $days, true);
        };

        /**
         * Carbon::class
         * $this->entry_start
         * $this->periodicity_end
         */
        $period = Carbon::parse($this->entry_start->toDateString())->daysUntil(
            $this->periodicity_end->toDateString()
        );

        /**
         * filter every x weeks
         * @param $date
         * @return bool
         */
        $filterWeek = function ($date) use ($repeat_week) {
            return $date->weekOfYear % $repeat_week === 0;
        };

        $period->excludeStartDate();
        $period->addFilter($filterDayOfWeek);

        if ($repeat_week > 1) {
            $period->addFilter($filterWeek);
        }

        return $period;
    }

    /**
     * @throws \Exception
     * @todo
     * https://stackoverflow.com/questions/57479939/php-carbon-every-monday-and-tuesday-every-2-weeks/57506714#57506714
     */
    protected function brouillon()
    {
        $start = new \DateTime('now');
        $end = clone $start;
        $end->modify('+4 month');
        $days = ['Monday', 'Tuesday'];
        foreach (CarbonPeriod::create($start, CarbonInterval::weeks(2), $end, CarbonPeriod::IMMUTABLE) as $baseDate) {
            foreach ($days as $dayName) {
                $date = $baseDate->is($dayName) ? $baseDate : $baseDate->next($dayName);
                dump($date);
            }
        }
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
