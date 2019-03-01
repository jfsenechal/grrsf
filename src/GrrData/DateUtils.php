<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 21:55
 */

namespace App\GrrData;


class DateUtils
{
    public static function getJoursSemaine()
    {
        return ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    }

    public static function getHeures()
    {
        return range(0, 23);
    }

    public static function getAffichageFormat()
    {
        return ['Affichage 12 h', 'Affichage 24h'];
    }
}