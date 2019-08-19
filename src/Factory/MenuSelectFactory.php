<?php


namespace App\Factory;


use App\Navigation\MenuSelect;

class MenuSelectFactory
{
    public function createNew(): MenuSelect
    {
        return new MenuSelect();
    }
}