<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 23:49
 */

namespace App\GrrData;


class RoomData
{
    public static function whoCanSee()
    {
        return [
            "N'importe qui allant sur le site même s'il n'est pas connecté",
            "Il faut obligatoirement être connecté, même en simple visiteur.",
            "Il faut obligatoirement être connecté et avoir le statut \"utilisateur",
            "Il faut obligatoirement être connecté et être au moins gestionnaire d'une ressource",
            "Il faut obligatoirement se connecter et être au moins administrateur du domaine",
            "Il faut obligatoirement être connecté et être administrateur de site",
            "Il faut obligatoirement être connecté et être administrateur général",
        ];
    }

    public static function typeAffichageReser()
    {
        return [
            "la durée de la réservation",
            "la date/heure de fin de réservation",
        ];
    }

    public static function qui_peut_reserver_pour()
    {
        return [
            5 => "personne",
            4 => "uniquement les administrateurs restreints",
            3 => "les gestionnaires de la ressource",
            2 => "tous les utilisateurs",
        ];
    }
}