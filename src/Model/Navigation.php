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

    /**
     * @return string
     */
    public function getPreviousButton(): string
    {
        return $this->previousButton;
    }

    /**
     * @param string $previousButton
     *
     * @return Navigation
     */
    public function setPreviousButton(string $previousButton): self
    {
        $this->previousButton = $previousButton;

        return $this;
    }

    /**
     * @return string
     */
    public function getNextButton(): string
    {
        return $this->nextButton;
    }

    /**
     * @param string $nextButton
     *
     * @return Navigation
     */
    public function setNextButton(string $nextButton): self
    {
        $this->nextButton = $nextButton;

        return $this;
    }

    /**
     * @return array
     */
    public function getMonths(): array
    {
        return $this->months;
    }

    /**
     * @param array $months
     *
     * @return Navigation
     */
    public function setMonths(array $months): self
    {
        $this->months = $months;

        return $this;
    }

    public function addMonth(string $month)
    {
        $this->months[] = $month;
    }

    /**
     * @return CarbonInterface
     */
    public function getToday(): CarbonInterface
    {
        return $this->today;
    }

    /**
     * @param CarbonInterface $today
     * @return Navigation
     */
    public function setToday(CarbonInterface $today): self
    {
        $this->today = $today;

        return $this;
    }

}
