<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Events\AuthorizationModelEvent;
use App\Form\Security\AuthorizationRoomType;
use App\Handler\HandlerAuthorizationArea;
use App\Manager\AuthorizationManager;
use App\Model\AuthorizationModel;
use App\Repository\Security\AuthorizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/authorization/room")
 */
class AuthorizationRoomController extends AbstractController
{
    /**
     * @var HandlerAuthorizationArea
     */
    private $handlerAuthorizationArea;
    /**
     * @var AuthorizationRepository
     */
    private $userAuthorizationRepository;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var AuthorizationManager
     */
    private $authorizationManager;

    public function __construct(
        AuthorizationManager $authorizationManager,
        HandlerAuthorizationArea $handlerUserManagerArea,
        AuthorizationRepository $userAuthorizationRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->handlerAuthorizationArea = $handlerUserManagerArea;
        $this->userAuthorizationRepository = $userAuthorizationRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->authorizationManager = $authorizationManager;
    }

    /**
     *
     * @Route("/new/user/{id}", name="grr_authorization_from_room", methods={"GET", "POST"})
     * @param Request $request
     * @param Room $room
     * @return Response
     */
    public function new(Request $request, Room $room): Response
    {
        $authorizationAreaModel = new AuthorizationModel();
        $authorizationAreaModel->setRooms([$room]);

        $form = $this->createForm(AuthorizationRoomType::class, $authorizationAreaModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->handlerAuthorizationArea->handleNewUserManagerResource($form);

            $authorizationEvent = new AuthorizationModelEvent($authorizationAreaModel);
            $this->eventDispatcher->dispatch($authorizationEvent, AuthorizationModelEvent::NEW_SUCCESS);

            return $this->redirectToRoute('grr_authorization_show_by_room', ['id' => $room->getId()]);
        }

        return $this->render(
            'security/authorization/room/new.html.twig',
            [
                'authorizationArea' => $authorizationAreaModel,
                'room' => $room,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_authorization_show_by_room", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        $authorizations = $this->userAuthorizationRepository->findByRoom($room);

        return $this->render(
            'security/authorization/room/show.html.twig',
            [
                'room' => $room,
                'authorizations' => $authorizations,
            ]
        );
    }
}
