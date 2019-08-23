<?php


namespace App\Setting;


class SettingsRoom
{
    const DISPLAY_TYPE_FORM_RESERVATION_DURATION = 0;
    const DISPLAY_TYPE_FORM_RESERVATION_DATE_END = 1;

    public static function whoCanSee()
    {
        return [
            "N'importe qui allant sur le site même s'il n'est pas connecté",
            'Il faut obligatoirement être connecté, même en simple visiteur.',
            'Il faut obligatoirement être connecté et avoir le statut "utilisateur',
            "Il faut obligatoirement être connecté et être au moins gestionnaire d'une ressource",
            'Il faut obligatoirement se connecter et être au moins administrateur du domaine',
            'Il faut obligatoirement être connecté et être administrateur de site',
            'Il faut obligatoirement être connecté et être administrateur général',
        ];
    }

    public static function typeAffichageReser()
    {
        return [
            self::DISPLAY_TYPE_FORM_RESERVATION_DURATION => 'setting.room.display.type_form_reservation_duree',
            self::DISPLAY_TYPE_FORM_RESERVATION_DATE_END => 'setting.room.display.type_form_reservation_endtime',
        ];
    }

    public static function qui_peut_reserver_pour()
    {
        return [
            5 => 'personne',
            4 => 'uniquement les administrateurs restreints',
            3 => 'les gestionnaires de la ressource',
            2 => 'tous les utilisateurs',
        ];
    }
}