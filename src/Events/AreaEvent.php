<?php

namespace App\Events;

use App\Entity\Area;
use Symfony\Contracts\EventDispatcher\Event;

class AreaEvent extends Event
{
    const NEW_SUCCESS = 'grr.area.new.success';
    const EDIT_SUCCESS = 'grr.area.edit.success';
    const DELETE_SUCCESS = 'grr.area.delete.success';

    /**
     * @var Area
     */
    private $area;

    public function __construct(Area $area)
    {
        $this->area = $area;
    }

    public function getArea(): Area
    {
        return $this->area;
    }
}
