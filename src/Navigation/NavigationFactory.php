<?php

namespace App\Navigation;

use App\Model\Navigation;

class NavigationFactory
{
    public function createNew(): Navigation
    {
        return new Navigation();
    }
}
