<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 25/03/19
 * Time: 20:25
 */

namespace App\Model;

use App\Entity\Room;
use App\Service\LocalHelper;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ArrayCollection|Day[]
     */
    protected $data_days;

    /**
     * @var ArrayCollection|RoomModel[]
     */
    protected $rooms;

    /**
     * @return RoomModel[]|ArrayCollection
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @param RoomModel[]|ArrayCollection $rooms
     */
    public function setRooms($rooms): void
    {
        $this->rooms = $rooms;
    }

    public function __construct($time = null, $tz = null)
    {
        parent::__construct($time, $tz);
        $this->data_days = new ArrayCollection();
        $this->rooms = new ArrayCollection();
    }

    public static function createWithLocal(int $year, int $week): self
    {
        Assert::greaterThan($year, 0);
        Assert::greaterThan($week, 0);

        $date = Carbon::create($year);
        $date->locale(LocalHelper::getDefaultLocal());
        $date->setISODate($year, $week);
        //$date->isoWeek($week, Carbon::MONDAY);

        $weekModel = new self();

        $weekModel->startDate = $date;
        $weekModel->endDate = $date->copy()->endOfWeek();

        return $weekModel;
    }

    /**
     * @return CarbonPeriod
     *
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

    /**
     * @return Collection|Day[]
     */
    public function getDataDays(): Collection
    {
        return $this->data_days;
    }

    public function addDataDay(Day $day): self
    {
        if (!$this->data_days->contains($day)) {
            $this->data_days[] = $day;
        }

        return $this;
    }

}