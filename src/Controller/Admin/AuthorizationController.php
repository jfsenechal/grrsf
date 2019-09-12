<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Events\AuthorizationEvent;
use App\Manager\AuthorizationManager;
use App\Repository\Security\AuthorizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/authorization")
 */
class AuthorizationController extends AbstractController
{
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
        AuthorizationRepository $userAuthorizationRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userAuthorizationRepository = $userAuthorizationRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->authorizationManager = $authorizationManager;
    }

    /**
     * @Route("/delete", name="grr_authorization_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $id = $request->get('idauth');
        $token = $request->get('_tokenauth');
        $urlBack = $request->get('_urlback', '/');

        $userAuthorization = $this->userAuthorizationRepository->find($id);

        if (!$userAuthorization) {
            $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('delete'.$userAuthorization->getId(), $token)) {

            $this->authorizationManager->remove($userAuthorization);
            $this->authorizationManager->flush();

            $authorizationEvent = new AuthorizationEvent($userAuthorization);
            $this->eventDispatcher->dispatch($authorizationEvent, AuthorizationEvent::DELETE_SUCCESS);
        } else {
            $this->addFlash('danger', 'authorization.flash.model.delete.error');
        }

        return $this->redirect($urlBack);
    }

    /**
     * @Route("/room/{id}", name="grr_authorization_show_by_room", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        $authorizations = $this->userAuthorizationRepository->findByRoom($room);
        $urlBack = $this->generateUrl('grr_authorization_show_by_user', ['id' => $room->getId()]);

        return $this->render(
            '@grr_security/authorization/room/show.html.twig',
            [
                'room' => $room,
                'authorizations' => $authorizations,
                'url_back' => $urlBack,
            ]
        );
    }

}
