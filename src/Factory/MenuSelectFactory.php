<?php


namespace App\Factory;


use App\Navigation\MenuSelect;

class MenuSelectFactory
{
    public static function createNew(): MenuSelect
    {
        return new MenuSelect();
    }
}