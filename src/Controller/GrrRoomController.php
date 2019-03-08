<?php

namespace App\Controller;

use App\Entity\GrrRoom;
use App\Factory\GrrRoomFactory;
use App\Form\GrrRoomType;
use App\Manager\GrrRoomManager;
use App\Repository\GrrRoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/room")
 */
class GrrRoomController extends AbstractController
{
    /**
     * @var GrrRoomFactory
     */
    private $grrRoomFactory;
    /**
     * @var GrrRoomRepository
     */
    private $grrRoomRepository;
    /**
     * @var GrrRoomManager
     */
    private $grrRoomManager;

    public function __construct(
        GrrRoomFactory $grrRoomFactory,
        GrrRoomRepository $grrRoomRepository,
        GrrRoomManager $grrRoomManager
    ) {
        $this->grrRoomFactory = $grrRoomFactory;
        $this->grrRoomRepository = $grrRoomRepository;
        $this->grrRoomManager = $grrRoomManager;
    }

    /**
     * @Route("/", name="grr_room_index", methods={"GET"})
     */
    public function index(): Response
    {
        $grrRooms = $this->grrRoomRepository->findAll();

        return $this->render(
            'grr_room/index.html.twig',
            [
                'grr_rooms' => $grrRooms,
            ]
        );
    }

    /**
     * @Route("/new", name="grr_room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrRoom = new GrrRoom();
        $form = $this->createForm(GrrRoomType::class, $grrRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->grrRoomManager->insert($grrRoom);

            return $this->redirectToRoute('grr_room_index');
        }

        return $this->render(
            'grr_room/new.html.twig',
            [
                'grr_room' => $grrRoom,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_room_show", methods={"GET"})
     */
    public function show(GrrRoom $grrRoom): Response
    {
        return $this->render(
            'grr_room/show.html.twig',
            [
                'grr_room' => $grrRoom,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrRoom $grrRoom): Response
    {
        $form = $this->createForm(GrrRoomType::class, $grrRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->grrRoomManager->flush();

            return $this->redirectToRoute(
                'grr_room_index',
                [
                    'id' => $grrRoom->getId(),
                ]
            );
        }

        return $this->render(
            'grr_room/edit.html.twig',
            [
                'grr_room' => $grrRoom,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrRoom $grrRoom): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrRoom->getId(), $request->request->get('_token'))) {

            $this->grrRoomManager->remove($grrRoom);
            $this->grrRoomManager->flush();

        }

        return $this->redirectToRoute('grr_room_index');
    }
}
