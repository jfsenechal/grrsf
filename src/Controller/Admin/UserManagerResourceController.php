<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Entity\Security\UserManagerResource;
use App\Form\Security\UserManagerResourceType;
use App\Handler\HandlerUserManagerResource;
use App\Model\UserManagerResourceModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/manager/resource")
 */
class UserManagerResourceController extends AbstractController
{
    /**
     * @var HandlerUserManagerResource
     */
    private $handlerUserManagerResource;

    public function __construct(HandlerUserManagerResource $handlerUserManagerResource)
    {
        $this->handlerUserManagerResource = $handlerUserManagerResource;
    }

    /**
     * @Route("/new/index", name="grr_user_manager_index", methods={"GET", "POST"})
     * @Route("/new/user/{user}", name="grr_user_manager_from_user", methods={"GET", "POST"})
     * @Route("/new/area/{area}", name="grr_user_manager_from_area", methods={"GET", "POST"})
     * @Route("/new/room/{room}", name="grr_user_manager_from_room", methods={"GET", "POST"})
     * @ParamConverter("user", options={"mapping": {"user": "id"}})
     * @ParamConverter("area", options={"mapping": {"area": "id"}})
     * @ParamConverter("room", options={"mapping": {"room": "id"}})
     */
    public function new(Request $request, User $user = null, Area $area = null, Room $room = null): Response
    {
        $userManagerResource = new UserManagerResourceModel();

        if ($area) {
            $userManagerResource->setArea($area);
        }

        if ($room) {
            $userManagerResource->setRooms([$room]);
        }

        if ($user) {
            $userManagerResource->setUsers([$user]);
        }

        $form = $this->createForm(UserManagerResourceType::class, $userManagerResource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handlerUserManagerResource->handleNewUserManagerResource($form);

            if ($user) {
                return $this->redirectToRoute('grr_admin_user_show', ['id' => $user->getId()]);
            }

            if ($room) {
                return $this->redirectToRoute('grr_admin_room_show', ['id' => $room->getId()]);
            }

            if ($area) {
                return $this->redirectToRoute('grr_admin_area_show', ['id' => $area->getId()]);
            }
        }

        return $this->render(
            'security/user_manager_resource/new.html.twig',
            [
                'user_manager_resource' => $userManagerResource,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="security_user_manager_resource_show", methods={"GET"})
     */
    public function show(UserManagerResource $userManagerResource): Response
    {
        return $this->render(
            'security/user_manager_resource/show.html.twig',
            [
                'user_manager_resource' => $userManagerResource,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="security_user_manager_resource_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UserManagerResource $userManagerResource): Response
    {
        $form = $this->createForm(UserManagerResourceType::class, $userManagerResource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('security_user_manager_resource_index');
        }

        return $this->render(
            'security/user_manager_resource/edit.html.twig',
            [
                'user_manager_resource' => $userManagerResource,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="security_user_manager_resource_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserManagerResource $userManagerResource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userManagerResource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userManagerResource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('security_user_manager_resource_index');
    }
}
