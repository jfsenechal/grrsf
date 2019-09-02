<?php

namespace App\Navigation;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Setting\SettingsProvider;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class RessourceSelectedHelper.
 */
class RessourceSelectedHelper
{
    const AREA_DEFAULT_SESSION = 'area_seleted';
    const ROOM_DEFAULT_SESSION = 'room_seleted';

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var SettingsProvider
     */
    private $settingsProvider;
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(
        SessionInterface $session,
        Security $security,
        SettingsProvider $settingsProvider,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository
    ) {
        $this->session = $session;
        $this->security = $security;
        $this->settingsProvider = $settingsProvider;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * @return Area
     *
     * @throws \Exception
     */
    public function getArea(): Area
    {
        if ($this->session->has(self::AREA_DEFAULT_SESSION)) {
            $areaId = $this->session->get(self::AREA_DEFAULT_SESSION);

            if ($area = $this->areaRepository->find($areaId)) {
                return $area;
            }
        }

        /**
         * @var User
         */
        $user = $this->security->getUser();
        if (null !== $user) {
            if ($area = $user->getAreaDefault()) {
                return $area;
            }
        }

        if ($area = $this->settingsProvider->getDefaultArea()) {
            return $area;
        }

        $area = $this->areaRepository->findOneBy([], ['id' => 'ASC']);
        if (!$area) {
            throw new \Exception(
                'No area in database, populate database with this command: php bin/console grr:install-data'
            );
        }

        return $area;
    }

    /**
     * -1 = force all ressource.
     *
     * @return Room|null
     */
    public function getRoom(): ?Room
    {
        if ($this->session->has(self::ROOM_DEFAULT_SESSION)) {
            $roomId = $this->session->get(self::ROOM_DEFAULT_SESSION);
            if (-1 === $roomId) {
                return null;
            }
            if ($roomId) {
                return $this->roomRepository->find($roomId);
            }
        }

        /**
         * @var User
         */
        $user = $this->security->getUser();
        if (null !== $user) {
            if ($room = $user->getRoomDefault()) {
                return $room;
            }
        }

        if ($room = $this->settingsProvider->getDefaulRoom()) {
            return $room;
        }

        return null;
    }

    public function setSelected(int $area, int $room = null)
    {
        $this->session->set(self::AREA_DEFAULT_SESSION, $area);
        if ($room) {
            $this->session->set(self::ROOM_DEFAULT_SESSION, $room);
        } else {
            $this->session->remove(self::ROOM_DEFAULT_SESSION);
        }
    }
}
