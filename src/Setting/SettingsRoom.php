<?php

namespace App\Setting;

class SettingsRoom
{
    const DISPLAY_TYPE_FORM_RESERVATION_DURATION = 0;
    const DISPLAY_TYPE_FORM_RESERVATION_DATE_END = 1;

    /**
     * qui peux réserver pour.
     */
    const BOOKING_FOR_NONE = 1;
    const BOOKING_FOR_EVERY_BODY = 5;
    const BOOKING_FOR_ROOM_MANAGER = 3;
    const BOOKING_FOR_ADMINISTRATOR_RESTRICTED = 4;

    /**
     * Qui peut ajouter une entrée.
     */
    const  CAN_ADD_NO_RULE = 0;
    const  CAN_ADD_EVERY_BODY = 1;
    const  CAN_ADD_EVERY_CONNECTED = 2;
    const  CAN_ADD_EVERY_USER_ACTIVE = 3;
    const  CAN_ADD_EVERY_ROOM_ADMINISTRATOR = 4;
    const  CAN_ADD_EVERY_ROOM_MANAGER = 5;
    const  CAN_ADD_EVERY_AREA_ADMINISTRATOR = 6;
    const  CAN_ADD_EVERY_AREA_MANAGER = 7;
    const  CAN_ADD_EVERY_GRR_ADMINISTRATOR_SITE = 8;
    const  CAN_ADD_EVERY_GRR_ADMINISTRATOR = 9;

    /**
     * @return string[]
     */
    public static function whoCanAdd(): array
    {
        return [
            self::CAN_ADD_NO_RULE => 'room.authorization.form.select.norule',
            self::CAN_ADD_EVERY_BODY => 'room.authorization.form.select.everybody',
            self::CAN_ADD_EVERY_CONNECTED => 'room.authorization.form.select.everyconnected',
            self::CAN_ADD_EVERY_USER_ACTIVE => 'room.authorization.form.select.everyactive',
            self::CAN_ADD_EVERY_ROOM_MANAGER => 'room.authorization.form.select.everyroommanager',
            self::CAN_ADD_EVERY_AREA_ADMINISTRATOR => 'room.authorization.form.select.everyareaadministrator',
            self::CAN_ADD_EVERY_GRR_ADMINISTRATOR_SITE => 'room.authorization.form.select.everysite',
            self::CAN_ADD_EVERY_GRR_ADMINISTRATOR => 'room.authorization.form.select.everygrradministrator',
        ];
    }

    /**
     * @return string[]
     */
    public static function typeAffichageReser(): array
    {
        return [
            self::DISPLAY_TYPE_FORM_RESERVATION_DURATION => 'setting.room.display.type_form_reservation_duree',
            self::DISPLAY_TYPE_FORM_RESERVATION_DATE_END => 'setting.room.display.type_form_reservation_endtime',
        ];
    }

    /**
     * @return string[]
     */
    public static function whoCanAddFor(): array
    {
        return [
            self::BOOKING_FOR_NONE => 'room.addfor.none',
            self::BOOKING_FOR_ADMINISTRATOR_RESTRICTED => 'room.addfor.administratorrestricted',
            self::BOOKING_FOR_ROOM_MANAGER => 'room.addfor.roommanager',
            self::BOOKING_FOR_EVERY_BODY => 'room.addfor.everybody',
        ];
    }
}
