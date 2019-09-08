<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 8/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Migration;


use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;

class MigrationUtil
{
    public static function createArea(array $data): Area
    {
        $area = new Area();
        $area->setName($data['area_name']);
        $area->setIsPrivate(AreaMigration::transformBoolean($data['access']));
        $area->setOrderDisplay($data['order_display']);
        $area->setStartTime($data['morningstarts_area']);
        $area->setEndTime($data['eveningends_area']);
        $area->setTimeInterval(AreaMigration::transformToMinutes($data['resolution_area']));
        $area->setMinutesToAddToEndTime($data['eveningends_minutes_area']);
        $area->setWeekStart($data['weekstarts_area']);
        $area->setIs24HourFormat($data['twentyfourhour_format_area']);
        // $area->set($data['calendar_default_values']);
        //  $area->set(AreaMigration::transformBoolean($data['enable_periods']));
        $area->setDaysOfWeekToDisplay(AreaMigration::transformSelecteDays($data['display_days']));
        //  $area->set($data['id_type_par_defaut']);
        $area->setDurationMaximumEntry($data['duree_max_resa_area']);
        $area->setDurationDefaultEntry(AreaMigration::transformToMinutes($data['duree_par_defaut_reservation_area']));
        $area->setMaxBooking($data['max_booking']);

        return $area;
    }

    public static function createRoom(Area $area, array $data): Room
    {
        $room = new Room($area);
        $room->setName($data['name']);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);
        $room->set($data[]);

        return $room;
    }

    public static function createEntry(array $data): Entry
    {
        $entry = $entry = new Entry();
        $entry->setName($data['name']);
        $entry->setStartTime();
        $entry->setEndTime();
        $entry->setRoom();
        $entry->setBeneficiaire();
        $entry->setDescription();
        $entry->setCreatedBy();
        $entry->setStatutEntry();
        $entry->setType();
        $entry->setJours();

        return $entry;
    }

}