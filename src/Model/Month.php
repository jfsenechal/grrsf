<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/19
 * Time: 16:22
 */

namespace App\Model;

use App\Service\LocalHelper;
use Carbon\Carbon;
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
        $this->dateTimeImmutable = CarbonImmutable::create($year, $month, 01)->locale(LocalHelper::getDefaultLocal());

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
        return Carbon::parse($this->dateTimeImmutable->firstOfMonth()->toDateString())->daysUntil(
            $this->dateTimeImmutable->endOfMonth()->toDateString()
        );
    }

    public function getFirstDay(): CarbonInterface
    {
        return $this->dateTimeImmutable->firstOfMonth();
    }

    public function getLastDay(): CarbonInterface
    {
        return $this->dateTimeImmutable->lastOfMonth();
    }

    public function getNextMonth(): CarbonInterface
    {
        return $this->dateTimeImmutable->addMonth();
    }

    public function getPreviousMonth(): CarbonInterface
    {
        return $this->dateTimeImmutable->subMonth();
    }

    public function getNumeric(): int
    {
        return $this->dateTimeImmutable->month;
    }

    /**
     * @return array
     */
    function getWeeks()
    {
        $weeks = [];
        $firstDayMonth = $this->getFirstDay();

        $firstDayWeek = $firstDayMonth->copy()->startOfWeek()->toMutable();

        do {
            $weeks[] = $this->getWeek($firstDayWeek);// point at end ofWeek
            $firstDayWeek->nextWeekday();
        } while ($firstDayWeek->isSameMonth($firstDayMonth));

        return $weeks;
    }

    private function getWeek(CarbonInterface $date)
    {
        $debut = $date->toDateString();
        $fin = $date->endOfWeek()->toDateString();

        return Carbon::parse($debut)->daysUntil($fin);
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