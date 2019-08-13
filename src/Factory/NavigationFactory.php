<?php


namespace App\Factory;


use App\Model\Navigation;

class NavigationFactory
{
    public static function createNew(): Navigation
    {
        return new Navigation();
    }
}