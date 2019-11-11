<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 8/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Migration;

use DateTime;
use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\EntryType;
use App\Entity\Periodicity;
use App\Entity\Room;
use App\Entity\Security\Authorization;
use App\Entity\Security\User;
use App\Periodicity\PeriodicityConstant;

class MigrationFactory
{
    /**
     * @var MigrationUtil
     */
    private $migrationUtil;

    public function __construct(MigrationUtil $migrationUtil)
    {
        $this->migrationUtil = $migrationUtil;
    }

    public function createArea(array $data): Area
    {
        $area = new Area();
        $area->setName($this->migrationUtil->convertToUf8($data['area_name']));
        $area->setIsRestricted($this->migrationUtil->transformBoolean($data['access']));
        //$area->set($data['ip_adr']);
        $area->setOrderDisplay($data['order_display']);
        $area->setStartTime($data['morningstarts_area']);
        $area->setEndTime($data['eveningends_area']);
        $area->setTimeInterval($this->migrationUtil->transformToMinutes($data['resolution_area']));
        $area->setMinutesToAddToEndTime($data['eveningends_minutes_area']);
        $area->setWeekStart($data['weekstarts_area']);
        $area->setIs24HourFormat($data['twentyfourhour_format_area']);
        // $area->set($data['calendar_default_values']);
        //  $area->set(AreaMigration::transformBoolean($data['enable_periods']));
        $area->setDaysOfWeekToDisplay($this->migrationUtil->transformSelecteDays($data['display_days']));
        //  $area->set($data['id_type_par_defaut']);
        $area->setDurationMaximumEntry($data['duree_max_resa_area']);
        $area->setDurationDefaultEntry(
            $this->migrationUtil->transformToMinutes($data['duree_par_defaut_reservation_area'])
        );
        $area->setMaxBooking($data['max_booking']);

        return $area;
    }

    public function createRoom(Area $area, array $data): Room
    {
        $room = new Room($area);
        $room->setName($data['room_name']);
        $room->setDescription($data['description']);
        $room->setCapacity($data['capacity']);
        $room->setMaximumBooking($data['max_booking']);
        $room->setStatutRoom($data['statut_room']);
        $room->setShowFicRoom($this->migrationUtil->transformBoolean($data['show_fic_room']));
        $room->setPictureRoom($data['picture_room']);
        $room->setCommentRoom($data['comment_room']);
        $room->setShowComment($this->migrationUtil->transformBoolean($data['show_comment']));
        $room->setDelaisMaxResaRoom($data['delais_max_resa_room']);
        $room->setDelaisMinResaRoom($data['delais_min_resa_room']);
        $room->setAllowActionInPast($this->migrationUtil->transformBoolean($data['allow_action_in_past']));
        $room->setOrderDisplay($data['order_display']);
        $room->setDelaisOptionReservation($data['delais_option_reservation']);
        $room->setDontAllowModify($this->migrationUtil->transformBoolean($data['dont_allow_modify']));
        $room->setTypeAffichageReser($data['type_affichage_reser']);
        $room->setModerate($this->migrationUtil->transformBoolean($data['moderate']));
        $room->setQuiPeutReserverPour($data['qui_peut_reserver_pour']);
        $room->setActiveRessourceEmpruntee($this->migrationUtil->transformBoolean($data['active_ressource_empruntee']));
        $room->setRuleToAdd($this->migrationUtil->tranformToAuthorization($data['who_can_see']));

        return $room;
    }

    public function createEntry(array $resolveTypeEntries, array $data): Entry
    {
        $entry = $entry = new Entry();
        $entry->setName($data['name']);
        $entry->setStartTime($this->migrationUtil->converToDateTime($data['start_time']));
        $entry->setEndTime($this->migrationUtil->converToDateTime($data['end_time']));

        //  $entry->set($data['entry_type']);
        //  $entry->setPeriodicity($data['repeat_id']);
        $entry->setCreatedAt($this->migrationUtil->converToDateTime($data['timestamp']));
        $entry->setUpdatedAt(new DateTime());
        $entry->setCreatedBy($data['create_by']);
        $entry->setBeneficiaire($data['beneficiaire_ext']);
        $entry->setBeneficiaireExt($data['beneficiaire']);
        $entry->setType($this->migrationUtil->convertToTypeEntry($resolveTypeEntries, $data['type']));
        $entry->setDescription($data['description']);
        $entry->setStatutEntry($data['statut_entry']);
        $entry->setOptionReservation($data['option_reservation']);
        $entry->setOverloadDesc($data['overload_desc']);
        $entry->setModerate($this->migrationUtil->transformBoolean($data['moderate']));
        $entry->setJours($this->migrationUtil->transformBoolean($data['jours']));

        return $entry;
    }

    public function createPeriodicity(Entry $entry, array $data): Periodicity
    {
        $periodicity = new Periodicity($entry);
        $periodicity->setWeekRepeat($data['rep_num_weeks']);
        $periodicity->setType($data['rep_type']);
        $periodicity->setEndTime($this->migrationUtil->converToDateTime($data['end_date']));
        if (PeriodicityConstant::EVERY_WEEK === (int) $data['rep_type']) {
            $periodicity->setWeekDays($this->migrationUtil->transformRepOpt($data['id'], $data['rep_opt']));
        }

        return $periodicity;
    }

    public function createTypeEntry(array $data): EntryType
    {
        $type = new EntryType();
        $type->setName($data['type_name']);
        $type->setOrderDisplay($data['order_display']);
        $type->setColor($this->migrationUtil->tabColor($data['couleur']));
        $type->setLetter($data['type_letter']);
        $type->setDisponible($data['disponible']);

        return $type;
    }

    public function createUser(array $data): User
    {
        $user = new User();
        $user->setUsername($data['login']);
        $user->setName($data['nom']);
        $user->setFirstName($data['prenom']);
        $user->setEmail($data['email']);
        $user->setRoles($this->migrationUtil->transformRole($data['statut']));
        $user->setIsEnabled($this->migrationUtil->transformEtat($data['etat']));
        $user->setLanguageDefault($data['default_language']);
        //  $user->set($data['default_site']);
        //  $user->set($data['default_style']);
        //  $user->set($data['default_list_type']);
        //  $user->set($data['source']);

        return $user;
    }

    public function createAuthorization(array $data): Authorization
    {
        return new Authorization();
    }
}
