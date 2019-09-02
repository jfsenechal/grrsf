<?php

namespace App\Factory;

use App\Model\Navigation;

class NavigationFactory
{
    public function createNew(): Navigation
    {
        return new Navigation();
    }
}
