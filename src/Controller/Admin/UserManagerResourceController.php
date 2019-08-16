<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Entity\Security\UserManagerResource;
use App\Form\Security\UserManagerResourceType;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use App\Repository\Security\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/manager/resource")
 */
class UserManagerResourceController extends AbstractController
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        UserRepository $userRepository
    ) {
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/new/index", name="grr_user_manager_index", methods={"GET","POST"})
     * @Route("/new/user/{user}", name="grr_user_manager_from_user", methods={"GET","POST"})
     * @Route("/new/area/{area}", name="grr_user_manager_from_area", methods={"GET","POST"})
     * @Route("/new/room/{room}", name="grr_user_manager_from_room", methods={"GET","POST"})
     * @ParamConverter("user", options={"mapping"={"user"="id"}})
     * @ParamConverter("area", options={"mapping"={"area"="id"}})
     * @ParamConverter("room", options={"mapping"={"room"="id"}})
     */
    public function new(Request $request, User $user = null, Area $area = null, Room $room = null): Response
    {
        // $area = $this->areaRepository->findOneBy(['name' => 'E-square']);
        // $room = $this->roomRepository->findOneBy(['name' => 'Box']);
        // $user = $this->userRepository->findOneBy(['email' => 'jf@marche.be']);

        $userManagerResource = new UserManagerResource();
        if ($room) {
            dump($room);
            $userManagerResource->setRoom($room);
        }

        if ($area) {
            dump($area);
            $userManagerResource->setArea($area);
        }

        if ($user) {
            $userManagerResource->setUser($user);
        }

        $form = $this->createForm(UserManagerResourceType::class, $userManagerResource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('grr_home');
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
     * @Route("/{id}/edit", name="security_user_manager_resource_edit", methods={"GET","POST"})
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
