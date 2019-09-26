<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 10/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Checker;


use App\Entity\Security\UserAuthorization;
use App\Manager\AuthorizationManager;
use App\Repository\RoomRepository;
use App\Repository\Security\AuthorizationRepository;
use App\Repository\Security\UserRepository;

class MigrationChecker
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var AuthorizationRepository
     */
    private $authorizationRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var AuthorizationManager
     */
    private $authorizationManager;

    public function __construct(
        UserRepository $userRepository,
        AuthorizationRepository $authorizationRepository,
        AuthorizationManager $authorizationManager,
        RoomRepository $roomRepository
    ) {
        $this->userRepository = $userRepository;
        $this->authorizationRepository = $authorizationRepository;
        $this->roomRepository = $roomRepository;
        $this->authorizationManager = $authorizationManager;
    }

    /**
     * Vérifie si un utilisateur est admin d'une aréa
     * et si celui-ci est mis en tant que administrateur ou pas d'une room
     * de cet area
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function checkAreaAndRoomAdministrator()
    {
        $result = [];
        $i = 0;
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $authorizations = $this->authorizationRepository->findByUserAndAreaNotNull($user, true);
            foreach ($authorizations as $authorization) {
                $area = $authorization->getArea();
                $rooms = $this->roomRepository->findByArea($area);
                foreach ($rooms as $room) {
                    $admin = $this->authorizationRepository->findOneByUserAndRoom($user, $room);
                    if ($admin) {
                        $result[$i]['authorization'] = $authorization;
                        $result[$i]['user'] = $user;
                        $result[$i]['area'] = $area;
                        $result[$i]['room'] = $room;
                        $i++;
                    }
                }
            }
        }

        return $result;
    }

    /**
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function deleteDoublon()
    {
        foreach ($this->checkAreaAndRoomAdministrator() as $data) {
            $authorization = $data['authorization'];
            $this->authorizationManager->remove($authorization);
        }
        $this->authorizationManager->flush();
    }

}