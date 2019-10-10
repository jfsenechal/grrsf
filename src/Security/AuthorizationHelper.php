<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 26/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security;

use App\Entity\Area;
use App\Entity\Room;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use App\Repository\Security\AuthorizationRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthorizationHelper
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var AuthorizationRepository
     */
    private $authorizationRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var AreaRepository
     */
    private $areaRepository;

    public function __construct(
        Security $security,
        AuthorizationRepository $authorizationRepository,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository
    ) {
        $this->security = $security;
        $this->authorizationRepository = $authorizationRepository;
        $this->roomRepository = $roomRepository;
        $this->areaRepository = $areaRepository;
    }

    /**
     * @param UserInterface $user
     *
     * @return Area[]
     *
     * @throws \Exception
     */
    public function getAreasUserCanAdd(UserInterface $user)
    {
        if ($user->hasRole(SecurityRole::ROLE_GRR_ADMINISTRATOR)) {
            return $this->areaRepository->findAll();
        }

        $areas = [];
        $authorizations = $this->authorizationRepository->findByUser($user);
        foreach ($authorizations as $authorization) {
            if ($authorization->getArea()) {
                $areas[] = $authorization->getArea();
                continue;
            }
            if ($room = $authorization->getRoom()) {
                $area = $room->getArea();
                $areas[] = $area;
                continue;
            }
        }

        return $areas;
    }

    /**
     * @param UserInterface $user
     *
     * @return Room[]
     *
     * @throws \Exception
     */
    public function getRoomsUserCanAdd(UserInterface $user, ?Area $area = null)
    {
        if ($user->hasRole(SecurityRole::ROLE_GRR_ADMINISTRATOR)) {
            if ($area) {
                return $this->roomRepository->findByArea($area);
            }

            return $this->roomRepository->findAll();
        }

        $rooms = [[]];

        if ($area) {
            $authorizations = $this->authorizationRepository->findByUserAndArea($user, $area);
        } else {
            $authorizations = $this->authorizationRepository->findByUser($user);
        }

        foreach ($authorizations as $authorization) {
            $area = $authorization->getArea();
            if ($area) {
                $rooms[] = $area->getRooms()->toArray();
                continue;
            }
            if ($room = $authorization->getRoom()) {
                $rooms[] = [$room];
                continue;
            }
        }

        return array_merge(...$rooms);
    }
}
