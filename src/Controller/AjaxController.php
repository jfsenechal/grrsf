<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/03/19
 * Time: 17:35.
 */

namespace App\Controller;

use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use App\Security\AuthorizationHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class AjaxController extends AbstractController
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var AuthorizationHelper
     */
    private $authorizationHelper;

    public function __construct(
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        AuthorizationHelper $authorizationHelper
    ) {
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->authorizationHelper = $authorizationHelper;
    }

    /**
     * @Route("/ajax/getrooms", name="grr_ajax_getrooms")
     */
    public function ajaxRequestGetRooms(Request $request)
    {
        $areaId = (int) $request->get('id');
        $required = filter_var($request->get('isRequired'), FILTER_VALIDATE_BOOLEAN, false);
        $restricted = filter_var($request->get('isRestricted'), FILTER_VALIDATE_BOOLEAN, false);

        $area = $this->areaRepository->find($areaId);
        if (null === $area) {
            throw new InvalidParameterException('Area not found');
        }

        /*
         *
         */
        if (!$restricted) {
            $rooms = $this->roomRepository->findByArea($area);
        } else {
            $user = $this->getUser();
            if (!$user) {
                throw new InvalidParameterException('You must be login');
            }
            $rooms = $this->authorizationHelper->getRoomsUserCanAdd($user, $area);
        }

        return $this->render('ajax/_rooms_options.html.twig', ['rooms' => $rooms, 'required' => $required]);
    }
}
