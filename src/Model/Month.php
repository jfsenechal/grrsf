<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/19
 * Time: 16:22
 */

namespace App\Model;

use App\Factory\CarbonFactory;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Month extends Carbon
{
    /**
     * @var CarbonInterface
     */
    protected $firstDayImmutable;

    /**
     * @var ArrayCollection
     */
    protected $data_days;

    public function __construct($time = null, $tz = null)
    {
        parent::__construct($time, $tz);
        $this->data_days = new ArrayCollection();
    }

    public static function createJf(int $year, int $month): self
    {
        $monthModel = new self();
        $monthModel->firstDayImmutable = CarbonFactory::createImmutable($year, $month, 01);

        return $monthModel;
    }

    public function getFirstDayImmutable(): CarbonInterface
    {
        return $this->firstDayImmutable;
    }

    /**
     * @return CarbonPeriod
     *
     */
    public function getCalendarDays(): CarbonPeriod
    {
        return Carbon::parse($this->firstDayImmutable->firstOfMonth()->toDateString())->daysUntil(
            $this->firstDayImmutable->endOfMonth()->toDateString()
        );
    }

    /**
     * @return CarbonPeriod[]
     *
     */
    function getCalendarWeeks()
    {
        $weeks = [];
        $firstDayMonth = $this->firstOfMonth();

        $firstDayWeek = $firstDayMonth->copy()->startOfWeek()->toMutable();

        do {
            $weeks[] = $this->getCalendarWeek($firstDayWeek);// point at end ofWeek
            $firstDayWeek->nextWeekday();
        } while ($firstDayWeek->isSameMonth($firstDayMonth));

        return $weeks;
    }

    public function getCalendarWeek(CarbonInterface $date): CarbonPeriod
    {
        $debut = $date->toDateString();
        $fin = $date->endOfWeek()->toDateString();

        return Carbon::parse($debut)->daysUntil($fin);
    }

    public function addDataDay(Day $day): self
    {
        if (!$this->data_days->contains($day)) {
            $this->data_days[] = $day;
        }

        return $this;
    }

    /**
     * @return Collection|Day[]
     */
    public function getDataDays(): Collection
    {
        return $this->data_days;
    }

    public function groupDataDaysByWeeks()
    {
        $dataDays = $this->getDataDays();
        $weeks = [];
        foreach ($this->getCalendarWeeks() as $weekCalendar) {
            $days = [];
            foreach ($weekCalendar as $dayCalendar) {
                foreach ($dataDays as $dataDay) {
                    if ($dataDay->toDateString() == $dayCalendar->toDateString()) {
                        $days[] = $dataDay;
                    }
                }
            }
            $weeks[]['days'] = $days;
        }

        return $weeks;
    }

    public function groupDaysByWeeks()
    {
        $weeks = [];
        $calendarDays = $this->getCalendarDays();
        foreach ($this->getCalendarWeeks() as $weekCalendar) {
            $days = [];
            foreach ($weekCalendar as $dayCalendar) {
                foreach ($calendarDays as $dataDay) {
                    if ($dataDay->toDateString() == $dayCalendar->toDateString()) {
                        $days[] = $dataDay;
                    }
                }
            }
            $weeks[]['days'] = $days;
        }

        return $weeks;
    }
}