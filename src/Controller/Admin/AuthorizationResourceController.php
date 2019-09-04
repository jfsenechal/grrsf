<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Form\Security\AuthorizationResourceType;
use App\Handler\HandlerAuthorizationResource;
use App\Model\AuthorizationResourceModel;
use App\Repository\Security\AuthorizationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/authorization/resource")
 */
class AuthorizationResourceController extends AbstractController
{
    /**
     * @var HandlerAuthorizationResource
     */
    private $handlerAuthorizationResource;
    /**
     * @var AuthorizationRepository
     */
    private $userAuthorizationRepository;

    public function __construct(
        HandlerAuthorizationResource $handlerUserManagerResource,
        AuthorizationRepository $userAuthorizationRepository
    ) {
        $this->handlerAuthorizationResource = $handlerUserManagerResource;
        $this->userAuthorizationRepository = $userAuthorizationRepository;
    }


    /**
     *
     * @Route("/new/user/{user}", name="grr_authorization_resource_from_user", methods={"GET", "POST"})
     * @Route("/new/resource/area/{area}", name="grr_authorization_resource_from_are", methods={"GET", "POST"})
     * @Route("/new/resource/room/{room}", name="grr_authorization_resource_from_room", methods={"GET", "POST"})
     * @ParamConverter("user", options={"mapping": {"user": "id"}})
     * @ParamConverter("area", options={"mapping": {"area": "id"}})
     * @ParamConverter("room", options={"mapping": {"room": "id"}})
     * @param Request $request
     * @param User|null $user
     * @param Resource|null $room
     * @return Response
     */
    public function new(Request $request, User $user = null, Area $area = null, Room $room = null): Response
    {
        $authorizationResourceModel = new AuthorizationResourceModel();

        if ($area) {
            $authorizationResourceModel->setArea($area);
        }

        if ($room) {
            $authorizationResourceModel->addRoom($room);
            $authorizationResourceModel->setArea($room->getArea());
        }

        if ($user) {
            $authorizationResourceModel->setUsers([$user]);
        }

        $form = $this->createForm(AuthorizationResourceType::class, $authorizationResourceModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->handlerAuthorizationResource->handleNewUserManagerResource($form);

            if ($user) {
      //          return $this->redirectToRoute('grr_admin_user_show', ['id' => $user->getId()]);
            }

            if ($room) {
      //          return $this->redirectToRoute('grr_authorization_resource_show', ['id' => $room->getId()]);
            }

            if ($area) {
     //           return $this->redirectToRoute('grr_authorization_area_index', ['id' => $area->getId()]);
            }
        }

        return $this->render(
            'security/authorization_resource/new.html.twig',
            [
                'authorizationResource' => $authorizationResourceModel,
                'form' => $form->createView(),
                'room' => $room,
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_authorization_resource_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        $authorizations = $this->userAuthorizationRepository->findBy(['room' => $room]);

        return $this->render(
            'security/authorization_resource/show.html.twig',
            [
                'room' => $room,
                'authorizations' => $authorizations,
            ]
        );
    }


}
