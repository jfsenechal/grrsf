<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Room;
use App\Events\RoomEvent;
use App\Form\RoomType;
use App\Manager\RoomManager;
use App\Room\RoomFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var RoomManager
     */
    private $roomManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        RoomFactory $roomFactory,
        RoomManager $roomManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->roomFactory = $roomFactory;
        $this->roomManager = $roomManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/new/{id}", name="grr_admin_room_new", methods={"GET", "POST"})
     * @IsGranted("grr.area.new.room", subject="area")
     */
    public function new(Request $request, Area $area): Response
    {
        $room = $this->roomFactory->createNew($area);
        $room->setDontAllowModify(false);

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roomManager->insert($room);

            $roomEvent = new RoomEvent($room);
            $this->eventDispatcher->dispatch($roomEvent, RoomEvent::NEW_SUCCESS);

            return $this->redirectToRoute('grr_admin_room_show', ['id' => $room->getId()]);
        }

        return $this->render(
            '@grr_admin/room/new.html.twig',
            [
                'area' => $area,
                'room' => $room,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_room_show", methods={"GET"})
     * @IsGranted("grr.room.show", subject="room")
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
     * @Route("/{id}/edit", name="grr_admin_room_edit", methods={"GET", "POST"})
     * @IsGranted("grr.room.edit", subject="room")
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roomManager->flush();

            $roomEvent = new RoomEvent($room);
            $this->eventDispatcher->dispatch($roomEvent, RoomEvent::EDIT_SUCCESS);

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
     * @IsGranted("grr.room.delete", subject="room")
     */
    public function delete(Request $request, Room $room): Response
    {
        $area = $room->getArea();
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $this->roomManager->removeEntries($room);
            $this->roomManager->remove($room);
            $this->roomManager->flush();

            $roomEvent = new RoomEvent($room);
            $this->eventDispatcher->dispatch($roomEvent, RoomEvent::DELETE_SUCCESS);
        }

        return $this->redirectToRoute('grr_admin_area_show', ['id' => $area->getId()]);
    }
}
