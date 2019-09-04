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

    public function __construct(AreaRepository $areaRepository, RoomRepository $roomRepository)
    {
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * @Route("/ajax/getrooms", name="grr_ajax_getrooms")
     */
    public function ajaxRequestGetRooms(Request $request)
    {
        $areaId = $request->get('id');
        $required = $request->get('isRequired');

        $area = $this->areaRepository->find($areaId);
        if (null === $area) {
            throw new InvalidParameterException('Area not found');
        }
        $rooms = $this->roomRepository->findByArea($area);

        return $this->render('ajax/_rooms_options.html.twig', ['rooms' => $rooms, 'required' => $required]);
    }
}
