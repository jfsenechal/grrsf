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
use App\Entity\EntryType;
use App\Entity\Room;
use App\Repository\AreaRepository;
use App\Repository\EntryTypeRepository;
use App\Repository\RoomRepository;
use App\Repository\Security\UserRepository;
use App\Security\SecurityRole;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MigrationUtil
{
    /**
     * @var UserPasswordEncoderInterface
     */
    public $passwordEncoder;
    /**
     * @var AreaRepository
     */
    public $areaRepository;
    /**
     * @var RoomRepository
     */
    public $roomRepository;
    /**
     * @var UserRepository
     */
    public $userRepository;
    /**
     * @var EntryTypeRepository
     */
    private $entryTypeRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        UserRepository $userRepository,
        EntryTypeRepository $entryTypeRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
        $this->entryTypeRepository = $entryTypeRepository;
    }

    public function transformBoolean(string $value): bool
    {
        $value = strtolower($value);
        if ($value == 'y' OR $value == 'a') {
            return true;
        }

        return false;
    }

    /***
     * Transforme un string : yyyynnn en array
     * @param string $display_days
     * @return array
     */
    public function transformSelecteDays(string $display_days): array
    {
        $pattern = ['#y#', '#n#'];
        $replacements = [1, 0];
        $tab = str_split(strtolower($display_days), 1);
        $days = array_map(
            function ($a) use ($pattern, $replacements) {
                return (int)preg_replace($pattern, $replacements, $a);
            },
            $tab
        );

        return $days;
    }

    public function transformToMinutes(int $time)
    {
        if ($time <= 0) {
            return 0;
        }

        return $time / CarbonInterface::MINUTES_PER_HOUR;
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

    public function transformEtat(string $etat): bool
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

    public function transformRole(string $statut)
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

    public function checkUser($data): ?string
    {
        if ($data['email'] == '') {
            return 'Pas de mail pour '.$data['login'];
        }
        if ($this->userRepository->findOneBy(['email' => $data['email']])) {
            return $data['login'].' : Il exsite déjà un utilisateur avec cette email: '.$data['email'];
        }

        return null;
    }

    public function convertToUf8(string $text): string
    {
        $charset = mb_detect_encoding($text, null, true);
        if ($charset != 'UTF-8') {
            return mb_convert_encoding($text, 'UTF-8', $charset);
        }

        return $text;
    }

    public function tabColor(int $index)
    {
        $tab_couleur[1] = "#FFCCFF";
        $tab_couleur[2] = "#99CCCC";
        $tab_couleur[3] = "#FF9999";
        $tab_couleur[4] = "#FFFF99";
        $tab_couleur[5] = "#C0E0FF";
        $tab_couleur[6] = "#FFCC99";
        $tab_couleur[7] = "#FF6666";
        $tab_couleur[8] = "#66FFFF";
        $tab_couleur[9] = "#DDFFDD";
        $tab_couleur[10] = "#CCCCCC";
        $tab_couleur[11] = "#7EFF7E";
        $tab_couleur[12] = "#8000FF";
        $tab_couleur[13] = "#FFFF00";
        $tab_couleur[14] = "#FF00DE";
        $tab_couleur[15] = "#00FF00";
        $tab_couleur[16] = "#FF8000";
        $tab_couleur[17] = "#DEDEDE";
        $tab_couleur[18] = "#C000FF";
        $tab_couleur[19] = "#FF0000";
        $tab_couleur[20] = "#FFFFFF";
        $tab_couleur[21] = "#A0A000";
        $tab_couleur[22] = "#DAA520";
        $tab_couleur[23] = "#40E0D0";
        $tab_couleur[24] = "#FA8072";
        $tab_couleur[25] = "#4169E1";
        $tab_couleur[26] = "#6A5ACD";
        $tab_couleur[27] = "#AA5050";
        $tab_couleur[28] = "#FFBB20";

        if ($index) {
            return $tab_couleur[$index];
        }

        return $tab_couleur;
    }

    public function converToDateTime(string $start_time): \DateTime
    {
        $date = Carbon::createFromTimestamp($start_time);

        return $date->toDateTime();
    }

    public function convertToTypeEntry(string $letter): ?EntryType
    {
        return $this->entryTypeRepository->findOneBy(['letter' => $letter]);
    }

    public function convertToRoom($room_id)
    {
        $room = $this->roomRepository->findOneBy(['name' => $nameRoom]);
    }
}