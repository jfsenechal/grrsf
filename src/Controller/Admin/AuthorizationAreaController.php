<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Form\Security\AuthorizationAreaType;
use App\Handler\HandlerAuthorizationArea;
use App\Model\AuthorizationModel;
use App\Repository\Security\AuthorizationRepository;
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
     * @Route("/new/area/{id}", name="grr_authorization_from_area", methods={"GET", "POST"})
     * @param Request $request
     * @param Area|null $area
     * @return Response
     */
    public function new(Request $request, Area $area = null): Response
    {
        $authorizationAreaModel = new AuthorizationModel();

        if ($area) {
            $authorizationAreaModel->setArea($area);
        }

        $form = $this->createForm(AuthorizationAreaType::class, $authorizationAreaModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->handlerAuthorizationArea->handleNewUserManagerResource($form);

            if ($area) {
                return $this->redirectToRoute('grr_authorization_area_show', ['id' => $area->getId()]);
            }
        }

        return $this->render(
            'security/authorization/area/new.html.twig',
            [
                'area' => $area,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_authorization_area_show", methods={"GET"})
     */
    public function show(Area $area): Response
    {
        $authorizations = $this->userAuthorizationRepository->findByArea($area);

        return $this->render(
            'security/authorization/area/show.html.twig',
            [
                'area' => $area,
                'authorizations' => $authorizations,
            ]
        );
    }


}
