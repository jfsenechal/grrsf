<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 21/03/19
 * Time: 15:47.
 */

namespace App\Model;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class Navigation
{
    /**
     * @var string
     */
    protected $previousButton;
    /**
     * @var string
     */
    protected $nextButton;
    /**
     * @var array
     */
    protected $months;

    /**
     * @var CarbonInterface
     */
    protected $today;

    public function __construct()
    {
        $this->months = [];
        $this->today = Carbon::today();
    }

    public function getPreviousButton(): string
    {
        return $this->previousButton;
    }

    /**
     * @return Navigation
     */
    public function setPreviousButton(string $previousButton): self
    {
        $this->previousButton = $previousButton;

        return $this;
    }

    public function getNextButton(): string
    {
        return $this->nextButton;
    }

    /**
     * @return Navigation
     */
    public function setNextButton(string $nextButton): self
    {
        $this->nextButton = $nextButton;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getMonths(): array
    {
        return $this->months;
    }

    /**
     * @return Navigation
     */
    public function setMonths(array $months): self
    {
        $this->months = $months;

        return $this;
    }

    public function addMonth(string $month): void
    {
        $this->months[] = $month;
    }

    public function getToday(): CarbonInterface
    {
        return $this->today;
    }
}
