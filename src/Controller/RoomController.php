<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Room;
use App\Factory\RoomFactory;
use App\Form\RoomType;
use App\Manager\RoomManager;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/room")
 */
class RoomController extends AbstractController
{
    /**
     * @var RoomFactory
     */
    private $roomFactory;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var RoomManager
     */
    private $roomManager;

    public function __construct(
        RoomFactory $roomFactory,
        RoomRepository $roomRepository,
        RoomManager $roomManager
    ) {
        $this->roomFactory = $roomFactory;
        $this->roomRepository = $roomRepository;
        $this->roomManager = $roomManager;
    }

    /**
     * @Route("/", name="grr_room_index", methods={"GET"})
     */
    public function index(): Response
    {
        $rooms = $this->roomRepository->findAll();

        return $this->render(
            'room/index.html.twig',
            [
                'rooms' => $rooms,
            ]
        );
    }

    /**
     * @Route("/new/{id}", name="grr_room_new", methods={"GET","POST"})
     */
    public function new(Request $request, Area $area): Response
    {
        $room = RoomFactory::createNew($area);
        RoomFactory::setDefaultValues($room);

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roomManager->insert($room);

            return $this->redirectToRoute('grr_room_show', ['id' => $room->getId()]);
        }

        return $this->render(
            'room/new.html.twig',
            [
                'room' => $room,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_room_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        return $this->render(
            'room/show.html.twig',
            [
                'room' => $room,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roomManager->flush();

            return $this->redirectToRoute(
                'grr_room_show',
                ['id' => $room->getId()]
            );
        }

        return $this->render(
            'room/edit.html.twig',
            [
                'room' => $room,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $this->roomManager->remove($room);
            $this->roomManager->flush();
        }

        return $this->redirectToRoute('grr_room_index');
    }
}
