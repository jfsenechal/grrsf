<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Room;
use App\Factory\RoomFactory;
use App\Form\RoomType;
use App\Manager\RoomManager;
use App\Repository\RoomRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/room")
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
     * @Route("/new/{id}", name="grr_admin_room_new", methods={"GET","POST"})
     * @IsGranted("new")
     */
    public function new(Request $request, Area $area): Response
    {
        $room = RoomFactory::createNew($area);

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roomManager->insert($room);

            return $this->redirectToRoute('grr_admin_room_show', ['id' => $room->getId()]);
        }

        return $this->render(
            '@grr_admin/room/new.html.twig',
            [
                'room' => $room,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_room_show", methods={"GET"})
     * @IsGranted("show", subject="room")
     */
    public function show(Room $room): Response
    {
        return $this->render(
            '@grr_admin/room/show.html.twig',
            [
                'room' => $room,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_admin_room_edit", methods={"GET","POST"})
     * @IsGranted("edit", subject="room")
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roomManager->flush();

            return $this->redirectToRoute(
                'grr_admin_room_show',
                ['id' => $room->getId()]
            );
        }

        return $this->render(
            '@grr_admin/room/edit.html.twig',
            [
                'room' => $room,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_room_delete", methods={"DELETE"})
     * @IsGranted("delete", subject="room")
     */
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $this->roomManager->removeEntries($room);
            $this->roomManager->remove($room);
            $this->roomManager->flush();
        }

        return $this->redirectToRoute('grr_admin_room_index');
    }
}
