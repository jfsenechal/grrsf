<?php

namespace App\Controller\Admin;

use App\Entity\Security\User;
use App\Events\AuthorizationEvent;
use App\Form\Security\AuthorizationUserType;
use App\Handler\HandlerAuthorization;
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
     * @var HandlerAuthorization
     */
    private $handlerAuthorization;
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
        HandlerAuthorization $handlerAuthorization,
        AuthorizationRepository $userAuthorizationRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->handlerAuthorization = $handlerAuthorization;
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

            $this->handlerAuthorization->handle($form);

            $authorizationEvent = new AuthorizationEvent($authorizationAreaModel);
            $this->eventDispatcher->dispatch($authorizationEvent, AuthorizationEvent::NEW_SUCCESS);

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
        $urlBack = $this->generateUrl('grr_authorization_show_by_user', ['id' => $user->getId()]);

        return $this->render(
            'security/authorization/user/show.html.twig',
            [
                'user' => $user,
                'authorizations' => $authorizations,
                'url_back' => $urlBack,
            ]
        );
    }

}
