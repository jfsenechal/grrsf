<?php

namespace App\Controller\Admin;

use App\Entity\Security\User;
use App\Factory\UserFactory;
use App\Form\Security\UserAdminType;
use App\Form\Security\UserNewType;
use App\Manager\UserManager;
use App\Repository\Security\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 * @IsGranted("ROLE_GRR_MANAGER_USER")
 */
class UserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $utilisateurRepository;

    /**
     * @var UserManager
     */
    private $utilisateurManager;
    /**
     * @var UserFactory
     */
    private $userFactory;

    public function __construct(
        UserRepository $utilisateurRepository,
        UserFactory $userFactory,
        UserManager $utilisateurManager
    ) {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->utilisateurManager = $utilisateurManager;
        $this->userFactory = $userFactory;
    }

    /**
     * @Route("/", name="grr_admin_user_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            '@grr_admin/user/index.html.twig',
            [
                'users' => $this->utilisateurRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="grr_admin_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $utilisateur = $this->userFactory->createNew();
        $form = $this->createForm(UserNewType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->utilisateurManager->encodePassword($utilisateur, $utilisateur->getPassword());
            $this->utilisateurRepository->insert($utilisateur);

            return $this->redirectToRoute('grr_admin_user_show', ['id' => $utilisateur->getId()]);
        }

        return $this->render(
            '@grr_admin/user/new.html.twig',
            [
                'user' => $utilisateur,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_user_show", methods={"GET"})
     */
    public function show(User $utilisateur): Response
    {
        return $this->render(
            '@grr_admin/user/show.html.twig',
            [
                'user' => $utilisateur,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_admin_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $utilisateur): Response
    {
        $form = $this->createForm(UserAdminType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->utilisateurRepository->flush();

            return $this->redirectToRoute(
                'grr_admin_user_show',
                ['id' => $utilisateur->getId()]
            );
        }

        return $this->render(
            '@grr_admin/user/edit.html.twig',
            [
                'user' => $utilisateur,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getEmail(), $request->request->get('_token'))) {
            $this->utilisateurRepository->remove($utilisateur);
            $this->utilisateurRepository->flush();
        }

        return $this->redirectToRoute('grr_admin_user_index');
    }
}
