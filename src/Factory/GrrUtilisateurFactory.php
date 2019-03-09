<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\GrrArea;
use App\Entity\GrrUtilisateur;
use App\GrrData\DateUtils;

class GrrUtilisateurFactory
{
    public function createNew(): GrrUtilisateur
    {
        return new GrrUtilisateur();
    }

}