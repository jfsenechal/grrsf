<?php


namespace App\Model;


class DurationModel
{
    const UNIT_TIME_MINUTES = 1;
    const UNIT_TIME_HOURS = 2;
    const UNIT_TIME_DAYS = 3;
    const UNIT_TIME_WEEKS = 4;

    /**
     * Encodage de la date de fin de l'entry.
     *
     * @return array
     */
    public static function getUnitsTime()
    {
        $units = [
            self::UNIT_TIME_MINUTES => 'unit.minutes',
            self::UNIT_TIME_HOURS => 'unit.hours',
            self::UNIT_TIME_DAYS => 'unit.days',
            self::UNIT_TIME_WEEKS => 'unit.weeks',
        ];

        return $units;
    }

    /**
     * @var integer
     */
    private $unit;

    /**
     * @var float;
     */
    private $time;

    /**
     * @var boolean
     */
    private $full_day;

    public function __construct()
    {
        $this->full_day = false;
    }

    /**
     * @return int
     */
    public function getUnit(): int
    {
        return $this->unit;
    }

    /**
     * @param int $unit
     * @return DurationModel
     */
    public function setUnit(int $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time;
    }

    /**
     * @param float $time
     * @return DurationModel
     */
    public function setTime(float $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFullDay(): bool
    {
        return $this->full_day;
    }

    /**
     * @param bool $full_day
     * @return DurationModel
     */
    public function setFullDay(bool $full_day): self
    {
        $this->full_day = $full_day;

        return $this;
    }
}