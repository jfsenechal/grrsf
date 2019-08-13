<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/19
 * Time: 16:22.
 */

namespace App\Model;

use App\Helper\LocalHelper;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @todo https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/index.html
 * Class Month
 */
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

    public static function init(int $year, int $month, $day = 01): self
    {
        $monthModel = new self();
        $monthModel->setDate($year, $month, $day);
        $monthModel->locale(LocalHelper::getDefaultLocal());
        $monthModel->firstDayImmutable = $monthModel->toImmutable();

        return $monthModel;
    }

    public function getFirstDayImmutable(): CarbonInterface
    {
        return $this->firstDayImmutable;
    }

    public function previousYear()
    {
        return $this->firstDayImmutable->subYear();
    }

    public function nextYear()
    {
        return $this->firstDayImmutable->addYear();
    }

    public function previousMonth()
    {
        return $this->firstDayImmutable->subMonth();
    }

    public function nextMonth()
    {
        return $this->firstDayImmutable->addMonth();
    }

    /**
     * @return CarbonPeriod
     */
    public function getCalendarDays(): CarbonPeriod
    {
        return Carbon::parse($this->firstDayImmutable->firstOfMonth()->toDateString())->daysUntil(
            $this->firstDayImmutable->endOfMonth()->toDateString()
        );
    }

    /**
     * @return CarbonPeriod[]
     */
    public function getCalendarWeeks()
    {
        $weeks = [];
        $firstDayMonth = $this->firstOfMonth();

        $firstDayWeek = $firstDayMonth->copy()->startOfWeek()->toMutable();

        do {
            $weeks[] = $this->getCalendarWeek($firstDayWeek); // point at end ofWeek
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
                    if ($dataDay->toDateString() === $dayCalendar->toDateString()) {
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
                    if ($dataDay->toDateString() === $dayCalendar->toDateString()) {
                        $days[] = $dataDay;
                    }
                }
            }
            $weeks[]['days'] = $days;
        }

        return $weeks;
    }
}
