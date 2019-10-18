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

use App\Entity\Area;
use App\Entity\EntryType;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\EntryTypeRepository;
use App\Repository\RoomRepository;
use App\Repository\Security\AuthorizationRepository;
use App\Repository\Security\UserRepository;
use App\Security\SecurityRole;
use App\Setting\SettingsRoom;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MigrationUtil
{
    const FOLDER_CACHE = __DIR__.'/../../var/cache/';

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
    /**
     * @var AuthorizationRepository
     */
    private $authorizationRepository;
    /**
     * @var EntryRepository
     */
    public $entryRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        UserRepository $userRepository,
        EntryTypeRepository $entryTypeRepository,
        EntryRepository $entryRepository,
        AuthorizationRepository $authorizationRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
        $this->entryTypeRepository = $entryTypeRepository;
        $this->authorizationRepository = $authorizationRepository;
        $this->entryRepository = $entryRepository;
    }

    public function transformBoolean(string $value): bool
    {
        $value = strtolower($value);
        if ('y' == $value or 'a' == $value) {
            return true;
        }

        return false;
    }

    /**
     * @return int[]
     */
    public function transformSelecteDays(string $display_days): array
    {
        $pattern = ['#y#', '#n#'];
        $replacements = [1, 0];
        $tab = str_split(strtolower($display_days), 1);
        $days = array_map(
            function ($a) use ($pattern, $replacements): int {
                return (int) preg_replace($pattern, $replacements, $a);
            },
            $tab
        );

        return $days;
    }

    /***
     * Transforme un string : 0011001 en array
     * @param string $datas
     * @return array
     * @throws \Exception
     */
    /**
     * @return int[]
     */
    public function transformRepOpt(int $id, string $datas): array
    {
        if (7 !== strlen($datas)) {
            throw new \Exception('Répétition pas 7 jours Repeat id :'.$id);
        }

        $days = [];
        $tab = str_split(strtolower($datas), 1);
        foreach ($tab as $key => $data) {
            if (1 === (int) $data) {
                $days[] = $key;
            }
        }

        return $days;
    }

    /**
     * @return int|float
     */
    public function transformToMinutes(int $time)
    {
        if ($time <= 0) {
            return 0;
        }

        return $time / CarbonInterface::MINUTES_PER_HOUR;
    }

    public function transformToArea(array $areas, int $areaId): ?Area
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

    public function transformToRoom(array $rooms, int $roomId): ?Room
    {
        if ($roomId < 1) {
            return null;
        }

        if (isset($rooms[$roomId])) {
            return $rooms[$roomId];
        }

        return null;
    }

    public function transformToUser(string $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }

    public function transformEtat(string $etat): bool
    {
        return 'actif' === $etat;
    }

    public function transformPassword($user, $password): ?string
    {
        if ('' === $password || null === $password) {
            return null;
        }

        return $this->passwordEncoder->encodePassword($user, 123456);
    }

    /**
     * @return string[]|null[]
     */
    public function transformRole(string $statut): array
    {
        switch ($statut) {
            case 'administrateur':
                $role = SecurityRole::ROLE_GRR_ADMINISTRATOR;
                break;
            case 'utilisateur':
                $role = SecurityRole::ROLE_GRR_ACTIVE_USER;
                break;
            case 'visiteur':
                $role = null; //par defaut dipose de @see SecurityRole::ROLE_GRR
                break;
            default:
                break;
        }

        return [$role];
    }

    public function checkUser($data): ?string
    {
        if ('' == $data['email']) {
            return 'Pas de mail pour '.$data['login'];
        }
        if ($this->userRepository->findOneBy(['email' => $data['email']])) {
            return $data['login'].' : Il exsite déjà un utilisateur avec cette email: '.$data['email'];
        }

        return null;
    }

    public function checkAuthorizationRoom(UserInterface $user, Room $room): ?string
    {
        if ($this->authorizationRepository->findOneBy(['user' => $user, 'room' => $room])) {
            return $user->getUsername().' à déjà un rôle pour la room: '.$room->getName();
        }

        return null;
    }

    public function convertToUf8(string $text): string
    {
        $charset = mb_detect_encoding($text, null, true);
        if ('UTF-8' != $charset) {
            return mb_convert_encoding($text, 'UTF-8', $charset);
        }

        return $text;
    }

    /**
     * @return string|string[]
     */
    public function tabColor(int $index)
    {
        $tab_couleur[1] = '#FFCCFF';
        $tab_couleur[2] = '#99CCCC';
        $tab_couleur[3] = '#FF9999';
        $tab_couleur[4] = '#FFFF99';
        $tab_couleur[5] = '#C0E0FF';
        $tab_couleur[6] = '#FFCC99';
        $tab_couleur[7] = '#FF6666';
        $tab_couleur[8] = '#66FFFF';
        $tab_couleur[9] = '#DDFFDD';
        $tab_couleur[10] = '#CCCCCC';
        $tab_couleur[11] = '#7EFF7E';
        $tab_couleur[12] = '#8000FF';
        $tab_couleur[13] = '#FFFF00';
        $tab_couleur[14] = '#FF00DE';
        $tab_couleur[15] = '#00FF00';
        $tab_couleur[16] = '#FF8000';
        $tab_couleur[17] = '#DEDEDE';
        $tab_couleur[18] = '#C000FF';
        $tab_couleur[19] = '#FF0000';
        $tab_couleur[20] = '#FFFFFF';
        $tab_couleur[21] = '#A0A000';
        $tab_couleur[22] = '#DAA520';
        $tab_couleur[23] = '#40E0D0';
        $tab_couleur[24] = '#FA8072';
        $tab_couleur[25] = '#4169E1';
        $tab_couleur[26] = '#6A5ACD';
        $tab_couleur[27] = '#AA5050';
        $tab_couleur[28] = '#FFBB20';

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

    public function convertToTypeEntry(array $resolveTypes, string $letter): ?EntryType
    {
        if (isset($resolveTypes[$letter])) {
            return $resolveTypes[$letter];
        }

        return null;
    }

    public function tranformToAuthorization(int $who_can_see): int
    {
        switch ($who_can_see) {
            case 0:
                $auth = SettingsRoom::CAN_ADD_EVERY_BODY;
                break;
            case 1:
                $auth = SettingsRoom::CAN_ADD_EVERY_CONNECTED;
                break;
            case 2:
                $auth = SettingsRoom::CAN_ADD_EVERY_USER_ACTIVE;
                break;
            case 3:
                $auth = SettingsRoom::CAN_ADD_EVERY_ROOM_MANAGER;
                break;
            case 4:
                $auth = SettingsRoom::CAN_ADD_EVERY_AREA_ADMINISTRATOR;
                break;
            case 5:
                $auth = SettingsRoom::CAN_ADD_EVERY_GRR_ADMINISTRATOR_SITE;
                break;
            case 6:
                $auth = SettingsRoom::CAN_ADD_EVERY_GRR_ADMINISTRATOR;
                break;
            default:
                $auth = 0;
                break;
        }

        return $auth;
    }

    public function writeFile($fileName, $content): void
    {
        $fileHandler = fopen(MigrationUtil::FOLDER_CACHE.$fileName, 'w');
        fwrite($fileHandler, $content);
        fclose($fileHandler);
    }

    /**
     * @return mixed[]
     */
    public function decompress(SymfonyStyle $io, string $content, string $type): array
    {
        $data = json_decode($content, true);

        if (!is_array($data)) {
            $io->error($type.' La réponse doit être un json: '.$content);

            return [];
        }

        if (isset($data['error'])) {
            $io->error('Une erreur est survenue: '.$data['error']);

            return [];
        }

        return $data;
    }
}
