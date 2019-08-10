<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/03/19
 * Time: 17:35.
 */

namespace App\Controller;

use App\Form\Type\SelectDayOfWeekTypeField;
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
        $area = $this->areaRepository->find($areaId);
        if ($area === null) {
            throw new InvalidParameterException('Area not found');
        }
        $rooms = $this->roomRepository->findByArea($area);

        return $this->render('ajax/_rooms_options.html.twig', ['rooms' => $rooms]);
    }

    /**
     * @Route("/ajax/getdays", name="grr_ajax_getdays")
     */
    public function ajaxRequestGetDaysToSelect(Request $request)
    {
        $areaId = $request->get('id');
        dump($areaId);
        $form = $this->createForm(SelectDayOfWeekTypeField::class);

        //  return $this->render('ajax/_select_days.html.twig', ['form' => $form->createView()]);
    }
}
