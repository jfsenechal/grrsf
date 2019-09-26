<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Form\Security\AuthorizationAreaType;
use App\Model\AuthorizationModel;
use App\Repository\Security\AuthorizationRepository;
use App\Security\HandlerAuthorization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/authorization/area")
 */
class AuthorizationAreaController extends AbstractController
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

    public function __construct(
        HandlerAuthorization $handlerAuthorization,
        AuthorizationRepository $authorizationRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->handlerAuthorization = $handlerAuthorization;
        $this->authorizationRepository = $authorizationRepository;
        $this->eventDispatcher = $eventDispatcher;
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

            $this->handlerAuthorization->handle($form);

            if ($area) {
                return $this->redirectToRoute('grr_authorization_area_show', ['id' => $area->getId()]);
            }
        }

        return $this->render(
            '@grr_security/authorization/area/new.html.twig',
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
        $authorizations = $this->authorizationRepository->findByArea($area);
        $urlBack = $this->generateUrl('grr_authorization_show_by_user', ['id' => $area->getId()]);

        return $this->render(
            '@grr_security/authorization/area/show.html.twig',
            [
                'area' => $area,
                'authorizations' => $authorizations,
                'url_back' => $urlBack,
            ]
        );
    }


}
