<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/19
 * Time: 16:22
 */

namespace App\Model;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class Month
{
    /**
     * @var CarbonInterface
     */
    protected $dateTimeImmutable;

    public function create(int $year, int $month): self
    {
        //$this->dateTimeImmutable = \DateTimeImmutable::createFromFormat('Y-m-d', $year.'-'.$month.'-01');
        $this->dateTimeImmutable = CarbonImmutable::create($year, $month, 01)->locale('fr_FR');

        return $this;
    }

    public function getDateTimeImmutable(): CarbonInterface
    {
        return $this->dateTimeImmutable;
    }

    /**
     * @return CarbonPeriod
     *
     */
    public function getDays(): CarbonPeriod
    {
        return new CarbonPeriod(
            $this->dateTimeImmutable->firstOfMonth()->toDateString(),
            '1 days',
            $this->dateTimeImmutable->endOfMonth()->toDateString()
        );
    }

    public function getFirstDay(): CarbonInterface
    {
        return $this->dateTimeImmutable->firstOfMonth();
    }

    public function getLastDay(): \DateTimeInterface
    {
        $date = clone $this->dateTimeImmutable;

        return $date->modify('last day of this month');
    }

    public function getNextMonth(): \DateTimeInterface
    {
        return $this->dateTimeImmutable->addMonth();
    }

    public function getPreviousMonth(): \DateTimeInterface
    {
        return $this->dateTimeImmutable->subMonth();
    }

    public function getNumeric(): int
    {
        return $this->dateTimeImmutable->month;
    }

    /**
     * Position au 1 du mois
     * @param CarbonInterface $firstDayMonth
     * @return array
     */
    function getWeeks(CarbonInterface $firstDayMonth)
    {
        $weeks = [];

        // $dt->isCurrentMonth();
        // $dt->isSameMonth($dt2); // same month of the same year of the given date
        $start = $firstDayMonth->copy()->startOfWeek()->toMutable();
        $mutable = $firstDayMonth->toMutable();

        while ($mutable->isSameMonth()) {
            $weeks[] = $this->getWeek($start);
            $start->addDay();
            $mutable->addWeek();
        }

        return $weeks;
    }

    private function getWeek(CarbonInterface $date)
    {
        $debut = $date->toDateString();
        $fin = $date->endOfWeek()->toDateString();

        $period = new CarbonPeriod(
            $debut,
            '1 days',
            $fin
        );

        return $period;
    }

    /**
     *
     * @return array
     * @throws \Exception
     */
    public function getDaysGroupByWeeks()
    {
        $weeks = [];

        foreach ($this->getDays() as $date) {
            $numericWeek = $date->format('W');

            $weeks[$numericWeek][] = $date;
        }

        return $weeks;
    }
}