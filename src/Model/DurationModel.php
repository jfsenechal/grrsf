<?php

namespace App\Model;

class DurationModel
{
    const UNIT_TIME_MINUTES = 1;
    const UNIT_TIME_HOURS = 2;
    const UNIT_TIME_DAYS = 3;
    const UNIT_TIME_WEEKS = 4;

    /**
     * Unité de temps.
     *
     * @var int
     */
    private $unit;

    /**
     * Le temps en flottant.
     *
     * @var float;
     */
    private $time;

    /**
     * @var bool
     */
    private $full_day;

    /**
     * Encodage de la date de fin de l'entry.
     *
     * @return string[]
     */
    public static function getUnitsTime(): array
    {
        $units = [
            self::UNIT_TIME_MINUTES => 'unit.minutes',
            self::UNIT_TIME_HOURS => 'unit.hours',
            self::UNIT_TIME_DAYS => 'unit.days',
            self::UNIT_TIME_WEEKS => 'unit.weeks',
        ];

        return $units;
    }

    public function __construct()
    {
        $this->full_day = false;
        $this->time = 0;
        $this->unit = self::UNIT_TIME_MINUTES;
    }

    public function getUnit(): int
    {
        return $this->unit;
    }

    /**
     * @return DurationModel
     */
    public function setUnit(int $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getTime(): float
    {
        return $this->time;
    }

    /**
     * @return DurationModel
     */
    public function setTime(float $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function isFullDay(): bool
    {
        return $this->full_day;
    }

    /**
     * @return DurationModel
     */
    public function setFullDay(bool $full_day): self
    {
        $this->full_day = $full_day;

        return $this;
    }
}
