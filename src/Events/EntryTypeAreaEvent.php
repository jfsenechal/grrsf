<?php

namespace App\Events;

use App\Entity\Area;
use Symfony\Contracts\EventDispatcher\Event;

class EntryTypeAreaEvent extends Event
{
    /**
     * @var Area
     */
    private $area;

    public function __construct(Area $area)
    {
        $this->area = $area;
    }

    /**
     * @return Area
     */
    public function getArea(): Area
    {
        return $this->area;
    }
}
