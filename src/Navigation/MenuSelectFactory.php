<?php

namespace App\Navigation;

class MenuSelectFactory
{
    public function createNew(): MenuSelect
    {
        return new MenuSelect();
    }
}
