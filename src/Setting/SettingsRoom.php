<?php

namespace App\Setting;

class SettingsRoom
{
    const DISPLAY_TYPE_FORM_RESERVATION_DURATION = 0;
    const DISPLAY_TYPE_FORM_RESERVATION_DATE_END = 1;

    /**
     * qui peux réserver pour
     */
    const BOOKING_FOR_NONE = 1;
    const BOOKING_FOR_EVERY_BODY = 5;
    const BOOKING_FOR_ROOM_MANAGER = 3;
    const BOOKING_FOR_ADMINISTRATOR_RESTRICTED = 4;

    /**
     * Qui peut ajouter une entrée
     */
    const  EVERY_BODY = 0;
    const  EVERY_CONNECTED = 1;
    const  EVERY_USER_ACTIVE = 2;
    const  EVERY_ROOM_ADMINISTRATOR = 3;
    const  EVERY_ROOM_MANAGER = 4;
    const  EVERY_AREA_ADMINISTRATOR = 5;
    const  EVERY_AREA_MANAGER = 6;
    const  EVERY_GRR_ADMINISTRATOR_SITE = 7;
    const  EVERY_GRR_ADMINISTRATOR = 8;

    public static function whoCanAdd()
    {
        return [
            self::EVERY_BODY => 'room.authorization.form.select.everybody',
            self::EVERY_CONNECTED => 'room.authorization.form.select.everyconnected',
            self::EVERY_USER_ACTIVE => 'room.authorization.form.select.everyactive',
            self::EVERY_ROOM_MANAGER => 'room.authorization.form.select.everyroommanager',
            self::EVERY_AREA_ADMINISTRATOR => 'room.authorization.form.select.everyareaadministrator',
            self::EVERY_GRR_ADMINISTRATOR_SITE => 'room.authorization.form.select.everysite',
            self::EVERY_GRR_ADMINISTRATOR => 'room.authorization.form.select.everygrradministrator',
        ];
    }

    public static function typeAffichageReser()
    {
        return [
            self::DISPLAY_TYPE_FORM_RESERVATION_DURATION => 'setting.room.display.type_form_reservation_duree',
            self::DISPLAY_TYPE_FORM_RESERVATION_DATE_END => 'setting.room.display.type_form_reservation_endtime',
        ];
    }

    public static function whoCanAddFor()
    {
        return [
            self::BOOKING_FOR_NONE => 'room.addfor.none',
            self::BOOKING_FOR_ADMINISTRATOR_RESTRICTED => 'room.addfor.administratorrestricted',
            self::BOOKING_FOR_ROOM_MANAGER => 'room.addfor.roommanager',
            self::BOOKING_FOR_EVERY_BODY => 'room.addfor.everybody',
        ];
    }
}
