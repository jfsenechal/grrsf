<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 28/03/19
 * Time: 14:49.
 */

namespace App\Model;

use Carbon\CarbonInterface;

class TimeSlot
{
    /**
     * @var CarbonInterface
     */
    protected $begin;
    /**
     * @var CarbonInterface
     */
    protected $end;

    public function __construct(CarbonInterface $begin, CarbonInterface $end)
    {
        $this->begin = $begin;
        $this->end = $end;
    }

    /**
     * @return CarbonInterface
     */
    public function getBegin(): CarbonInterface
    {
        return $this->begin;
    }

    /**
     * @return CarbonInterface
     */
    public function getEnd(): CarbonInterface
    {
        return $this->end;
    }

}
