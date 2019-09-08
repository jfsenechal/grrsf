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
use App\Entity\EntryType;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use App\Repository\Security\UserRepository;
use App\Security\SecurityRole;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MigrationUtil
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        UserRepository $userRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
    }


    public static function createArea(array $data): Area
    {
        $area = new Area();
        $area->setName($data['area_name']);
        $area->setIsPrivate(self::transformBoolean($data['access']));
        //$area->set($data['ip_adr']);
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
        $room->setName($data['room_name']);
        $room->setDescription($data['description']);
        $room->setCapacity($data['capacity']);
        $room->setMaximumBooking($data['max_booking']);
        $room->setStatutRoom($data['statut_room']);
        $room->setShowFicRoom(self::transformBoolean($data['show_fic_room']));
        $room->setPictureRoom($data['picture_room']);
        $room->setCommentRoom($data['comment_room']);
        $room->setShowComment(self::transformBoolean($data['show_comment']));
        $room->setDelaisMaxResaRoom($data['delais_max_resa_room']);
        $room->setDelaisMinResaRoom($data['delais_min_resa_room']);
        $room->setAllowActionInPast(self::transformBoolean($data['allow_action_in_past']));
        $room->setOrderDisplay($data['order_display']);
        $room->setDelaisOptionReservation($data['delais_option_reservation']);
        $room->setDontAllowModify(self::transformBoolean($data['dont_allow_modify']));
        $room->setTypeAffichageReser($data['type_affichage_reser']);
        $room->setModerate(self::transformBoolean($data['moderate']));
        $room->setQuiPeutReserverPour($data['qui_peut_reserver_pour']);
        $room->setActiveRessourceEmpruntee(self::transformBoolean($data['active_ressource_empruntee']));
        $room->setWhoCanSee($data['who_can_see']);

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

    public static function createTypeEntry(array $data): EntryType
    {
        $type = new EntryType();
        $type->setName($data['type_name']);
        $type->setOrderDisplay($data['order_display']);
        $type->setColor(self::tabColor($data['couleur']));
        $type->setLetter($data['type_letter']);
        $type->setDisponible($data['disponible']);

        return $type;
    }

    protected static function tabColor(int $index)
    {
        $tab_couleur[1] = "#FFCCFF";
        $tab_couleur[2] = "#99CCCC";
        $tab_couleur[3] = "#FF9999";
        $tab_couleur[4] = "#FFFF99"; # jaune p�le
        $tab_couleur[5] = "#C0E0FF"; # bleu-vert
        $tab_couleur[6] = "#FFCC99"; # p�che
        $tab_couleur[7] = "#FF6666"; # rouge
        $tab_couleur[8] = "#66FFFF"; # bleu "aqua"
        $tab_couleur[9] = "#DDFFDD"; # vert clair
        $tab_couleur[10] = "#CCCCCC"; # gris
        $tab_couleur[11] = "#7EFF7E"; # vert p�le
        $tab_couleur[12] = "#8000FF"; # violet
        $tab_couleur[13] = "#FFFF00"; # jaune
        $tab_couleur[14] = "#FF00DE"; # rose
        $tab_couleur[15] = "#00FF00"; # vert
        $tab_couleur[16] = "#FF8000"; # orange
        $tab_couleur[17] = "#DEDEDE"; # gris clair
        $tab_couleur[18] = "#C000FF"; # Mauve
        $tab_couleur[19] = "#FF0000"; # rouge vif
        $tab_couleur[20] = "#FFFFFF"; # blanc
        $tab_couleur[21] = "#A0A000"; # Olive verte
        $tab_couleur[22] = "#DAA520"; # marron goldenrod
        $tab_couleur[23] = "#40E0D0"; # turquoise
        $tab_couleur[24] = "#FA8072"; # saumon
        $tab_couleur[25] = "#4169E1";
        $tab_couleur[26] = "#6A5ACD";
        $tab_couleur[27] = "#AA5050";
        $tab_couleur[28] = "#FFBB20";

        if ($index) {
            return $tab_couleur[$index];
        }

        return $tab_couleur;
    }

    public static function transformBoolean(string $value): bool
    {
        $value = strtolower($value);
        if ($value == 'y' OR $value == 'a') {
            return true;
        }

        return false;
    }

    public static function createUser(array $data): User
    {
        $user = new User();
        $user->setUsername($data['login']);
        $user->setName($data['nom']);
        $user->setFirstName($data['prenom']);
        $user->setEmail($data['email']);
        $user->setRoles(self::transformRole($data['statut']));
        $user->setIsEnabled(self::transformEtat($data['etat']));
        $user->setLanguageDefault($data['default_language']);
        //  $user->set($data['default_site']);
        //  $user->set($data['default_style']);
        //  $user->set($data['default_list_type']);
        //  $user->set($data['source']);

        return $user;
    }

    public function transformDefaultArea(array $areas, int $areaId): ?Area
    {
        if ($areaId < 1) {
            return null;
        }

        foreach ($areas as $data) {
            if ($data['id'] == $areaId) {
                $nameArea = $data['area_name'];
                $area = $this->areaRepository->findOneBy(['name' => $nameArea]);
                if ($area) {
                    return $area;
                }
            }
        }

        return null;
    }


    public function transformDefaultRoom(array $rooms, int $roomId): ?Room
    {
        if ($roomId < 1) {
            return null;
        }

        foreach ($rooms as $data) {
            if ($data['id'] == $roomId) {
                $nameRoom = $data['room_name'];
                $room = $this->roomRepository->findOneBy(['name' => $nameRoom]);
                if ($room) {
                    return $room;
                }
            }
        }

        return null;
    }

    private static function transformEtat(string $etat): bool
    {
        return $etat === 'actif';
    }

    public function transformPassword($user, $password)
    {
        if ($password === '' || $password === null) {
            return null;
        }

        return $this->passwordEncoder->encodePassword($user, 123456);
    }

    private static function transformRole(string $statut)
    {
        switch ($statut) {
            case 'administrateur' :
                $role = SecurityRole::getRoleGrrAdministrator();
                break;
            case 'utilisateur':
                $role = SecurityRole::getRoleGrr();
                break;
            case 'visiteur':
                $role = SecurityRole::getRoleGrr();
                break;
            default:
                break;
        }

        return [$role];
    }

    public function checkUser($data) :?string
    {
        if ($data['email'] == '') {
            return 'Pas de mail pour '.$data['login'];
        }
        if ($this->userRepository->findOneBy(['email' => $data['email']])) {
            return $data['login'].' : Il exsite déjà un utilisateur avec cette email: '.$data['email'];
        }
        return null;
    }

}