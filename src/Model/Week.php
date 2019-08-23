<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 25/03/19
 * Time: 20:25.
 */

namespace App\Model;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Webmozart\Assert\Assert;

class Week extends Carbon
{
    /**
     * @var CarbonInterface
     */
    protected $startDate;
    /**
     * @var CarbonInterface
     */
    protected $endDate;

    public static function createWithLocal(int $year, int $week): self
    {
        Assert::greaterThan($year, 0);
        Assert::greaterThan($week, 0);

        $date = Carbon::create($year);
        $date->setISODate($year, $week);
        //$date->isoWeek($week, Carbon::MONDAY);

        $weekModel = new self();

        $weekModel->startDate = $date;
        $weekModel->endDate = $date->copy()->endOfWeek();

        return $weekModel;
    }

    /**
     * @return CarbonPeriod
     */
    public function getCalendarDays(): CarbonPeriod
    {
        return Carbon::parse($this->getFirstDay()->toDateString())->daysUntil($this->getLastDay()->toDateString());
    }

    public function getFirstDay(): CarbonInterface
    {
        return $this->startDate;
    }

    public function getLastDay(): CarbonInterface
    {
        return $this->endDate;
    }
}
