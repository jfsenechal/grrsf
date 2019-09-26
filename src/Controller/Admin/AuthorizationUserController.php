<?php

namespace App\Controller\Admin;

use App\Entity\Security\User;
use App\Form\Security\AuthorizationUserType;
use App\Manager\AuthorizationManager;
use App\Model\AuthorizationModel;
use App\Repository\Security\AuthorizationRepository;
use App\Security\HandlerAuthorization;
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
    private $authorizationRepository;
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
        AuthorizationRepository $authorizationRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->handlerAuthorization = $handlerAuthorization;
        $this->authorizationRepository = $authorizationRepository;
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
        $authorizationModel = new AuthorizationModel();
        $authorizationModel->setUsers([$user]);

        $form = $this->createForm(AuthorizationUserType::class, $authorizationModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->handlerAuthorization->handle($form);

            return $this->redirectToRoute('grr_authorization_show_by_user', ['id' => $user->getId()]);
        }

        return $this->render(
            '@grr_security/authorization/user/new.html.twig',
            [
                'authorizationArea' => $authorizationModel,
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
        $authorizations = $this->authorizationRepository->findByUser($user);
        $urlBack = $this->generateUrl('grr_authorization_show_by_user', ['id' => $user->getId()]);

        return $this->render(
            '@grr_security/authorization/user/show.html.twig',
            [
                'user' => $user,
                'authorizations' => $authorizations,
                'url_back' => $urlBack,
            ]
        );
    }

}
