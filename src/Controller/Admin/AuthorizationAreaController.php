<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Security\User;
use App\Entity\Security\UserAuthorization;
use App\Form\Security\AuthorizationAreaType;
use App\Form\Security\AuthorizationResourceType;
use App\Handler\HandlerAuthorizationArea;
use App\Model\AuthorizationAreaModel;
use App\Repository\Security\AuthorizationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/authorization/area")
 */
class AuthorizationAreaController extends AbstractController
{
    /**
     * @var HandlerAuthorizationArea
     */
    private $handlerAuthorizationArea;
    /**
     * @var AuthorizationRepository
     */
    private $userAuthorizationRepository;

    public function __construct(
        HandlerAuthorizationArea $handlerUserManagerArea,
        AuthorizationRepository $userAuthorizationRepository
    ) {
        $this->handlerAuthorizationArea = $handlerUserManagerArea;
        $this->userAuthorizationRepository = $userAuthorizationRepository;
    }

    /**
     * @Route("/new/index", name="grr_authorization_area_index", methods={"GET", "POST"})
     * @Route("/new/user/{user}", name="grr_authorization_area_from_user", methods={"GET", "POST"})
     * @Route("/new/area/{area}", name="grr_authorization_area_from_area", methods={"GET", "POST"})
     * @ParamConverter("user", options={"mapping": {"user": "id"}})
     * @ParamConverter("area", options={"mapping": {"area": "id"}})
     * @param Request $request
     * @param User|null $user
     * @param Area|null $area
     * @return Response
     */
    public function new(Request $request, User $user = null, Area $area = null): Response
    {
        $authorizationAreaModel = new AuthorizationAreaModel();

        if ($area) {
            $authorizationAreaModel->setArea($area);
        }

        if ($user) {
            $authorizationAreaModel->setUsers([$user]);
        }

        $authorizationAreaModel->setAreaAdministrator(true);

        $form = $this->createForm(AuthorizationAreaType::class, $authorizationAreaModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->handlerAuthorizationArea->handleNewUserManagerResource($form);

            if ($user) {
                return $this->redirectToRoute('grr_admin_user_show', ['id' => $user->getId()]);
            }

            if ($area) {
                return $this->redirectToRoute('grr_user_authorization_area_show', ['id' => $area->getId()]);
            }
        }

        return $this->render(
            'security/user_authorization_area/new.html.twig',
            [
                'authorizationArea' => $authorizationAreaModel,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_user_authorization_area_show", methods={"GET"})
     */
    public function show(Area $area): Response
    {
        $authorizations = $this->userAuthorizationRepository->findAll();

        return $this->render(
            'security/user_authorization_area/show.html.twig',
            [
                'area' => $area,
                'authorizations' => $authorizations,
            ]
        );
    }

    /**
     * @Route("/delete", name="grr_user_authorization_area_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $id = $request->get('idauth');
        $token = $request->get('_tokenauth');

        $userAuthorization = $this->userAuthorizationRepository->find($id);

        if (!$userAuthorization) {
            $this->createNotFoundException();
        }

        $area = $userAuthorization->getArea();

        if ($this->isCsrfTokenValid('delete'.$userAuthorization->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userAuthorization);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grr_user_authorization_area_show', ['id' => $area->getId()]);
    }
}
