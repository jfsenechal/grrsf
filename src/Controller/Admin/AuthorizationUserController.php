<?php

namespace App\Controller\Admin;

use App\Entity\Security\User;
use App\Events\AuthorizationModelEvent;
use App\Events\AuthorizationUserEvent;
use App\Form\Security\AuthorizationUserType;
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
 * @Route("/admin/authorization/user")
 */
class AuthorizationUserController extends AbstractController
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
     * @Route("/new/user/{id}", name="grr_authorization_from_user", methods={"GET", "POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function new(Request $request, User $user): Response
    {
        $authorizationAreaModel = new AuthorizationModel();
        $authorizationAreaModel->setUsers([$user]);

        $form = $this->createForm(AuthorizationUserType::class, $authorizationAreaModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->handlerAuthorizationArea->handleNewUserManagerResource($form);

            $authorizationEvent = new AuthorizationModelEvent($authorizationAreaModel);
            $this->eventDispatcher->dispatch($authorizationEvent, AuthorizationModelEvent::NEW_SUCCESS);

            return $this->redirectToRoute('grr_authorization_show_by_user', ['id' => $user->getId()]);
        }

        return $this->render(
            'security/authorization/user/new.html.twig',
            [
                'authorizationArea' => $authorizationAreaModel,
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_authorization_show_by_user", methods={"GET"})
     */
    public function show(User $user): Response
    {
        $authorizations = $this->userAuthorizationRepository->findByUser($user);

        return $this->render(
            'security/authorization/user/show.html.twig',
            [
                'user' => $user,
                'authorizations' => $authorizations,
            ]
        );
    }

}
