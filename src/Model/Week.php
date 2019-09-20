<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 25/03/19
 * Time: 20:25.
 */

namespace App\Model;

use App\I18n\LocalHelper;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Webmozart\Assert\Assert;

class Week
{
    /**
     * @var CarbonInterface
     */
    protected $first_day;
    /**
     * @var CarbonInterface
     */
    protected $last_day;

    public static function create(int $year, int $week): Week
    {
        Assert::greaterThan($year, 0);
        Assert::greaterThan($week, 0);

        $date = Carbon::create($year);
        $date->setISODate($year, $week);
        $date->locale(LocalHelper::getDefaultLocal());
        //$date->isoWeek($week, Carbon::MONDAY);

        $weekModel = new self();

        $weekModel->first_day = $date;
        $weekModel->last_day = $date->copy()->endOfWeek();

        return $weekModel;
    }

    /**
     * Retourne la liste des jours de la semaines
     * @return CarbonPeriod
     */
    public function getCalendarDays(): CarbonPeriod
    {
        return Carbon::parse($this->getFirstDay()->toDateString())->daysUntil(
            $this->getLastDay()->toDateString()
        )->locale(LocalHelper::getDefaultLocal());
    }

    public function getFirstDay(): CarbonInterface
    {
        return $this->first_day;
    }

    public function getLastDay(): CarbonInterface
    {
        return $this->last_day;
    }
}
