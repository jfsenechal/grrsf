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
        return $this->dateTimeImmutable->lastOfMonth();
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
     *
     * @param CarbonInterface $firstDayMonth Position au 1 du mois
     * @return array
     */
    function getWeeks()
    {
        $weeks = [];
        $firstDayMonth = $this->getFirstDay();

        $firstDayWeek = $firstDayMonth->copy()->startOfWeek()->toMutable();
        $firstDayMonthMutable = $firstDayMonth->toMutable();

        do  {
            $weeks[] = $this->getWeek($firstDayWeek);// point at end ofWeek
            $firstDayWeek->addDay();
            $firstDayMonthMutable->addWeek();
        } while ($firstDayWeek->isSameMonth($firstDayMonth));

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