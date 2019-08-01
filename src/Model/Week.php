<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 25/03/19
 * Time: 20:25
 */

namespace App\Model;

use App\Service\LocalHelper;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Webmozart\Assert\Assert;

class Week
{
    /**
     * @var CarbonInterface
     */
    protected $startDate;

    /**
     * @var CarbonInterface
     */
    protected $endDate;

    /**
     * @var array
     */
    protected $data = [];

    public function create(int $year, int $week): self
    {
        Assert::greaterThan($year, 0);
        Assert::greaterThan($week, 0);

        $date = Carbon::create($year)->locale(LocalHelper::getDefaultLocal());
        $date->setISODate($year, $week);
        //$date->isoWeek($week, Carbon::MONDAY);

        $this->startDate = $date;
        $this->endDate = $date->copy()->endOfWeek();

        return $this;
    }

    /**
     * @return CarbonPeriod
     *
     */
    public function getDays(): CarbonPeriod
    {
        $period = new CarbonPeriod(
            $this->getFirstDay()->toDateString(),
            '1 days',
            $this->getLastDay()->toDateString()
        );

        return $period;
    }

    public function getFirstDay(): CarbonInterface
    {
        return $this->startDate;
    }

    public function getLastDay(): CarbonInterface
    {
        return $this->endDate;
    }

    public function setDaysData(array $days)
    {
        $this->data = $days;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

}