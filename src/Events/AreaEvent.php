<?php

namespace App\Events;

use App\Entity\Area;
use Symfony\Contracts\EventDispatcher\Event;

class AreaEvent extends Event
{
    const AREA_NEW_INITIALIZE = 'grr.area.new.initialize';
    const AREA_NEW_SUCCESS = 'grr.area.new.success';
    const AREA_NEW_COMPLETE = 'grr.area.new.complete';
    const AREA_EDIT_SUCCESS = 'grr.area.edit.success';
    const AREA_DELETE_SUCCESS = 'grr.area.delete.success';

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
