<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/utilisateurs")
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
            '@grr_admin/utilisateurs/index.html.twig',
            [
                'utilisateurs' => $this->utilisateurRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="grr_admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $utilisateur = $this->userFactory->createNew();
        $form = $this->createForm(UserType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->utilisateurManager->encodePassword($utilisateur, $utilisateur->getPassword());
            $this->utilisateurRepository->insert($utilisateur);

            return $this->redirectToRoute('grr_admin_user_show', ['id' => $utilisateur->getId()]);
        }

        return $this->render(
            '@grr_admin/utilisateurs/new.html.twig',
            [
                'utilisateur' => $utilisateur,
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
            '@grr_admin/utilisateurs/show.html.twig',
            [
                'utilisateur' => $utilisateur,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $utilisateur): Response
    {
        $form = $this->createForm(UserEditType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->utilisateurRepository->flush();

            return $this->redirectToRoute(
                'grr_admin_user_show',
                ['id' => $utilisateur->getId()]
            );
        }

        return $this->render(
            '@grr_admin/utilisateurs/edit.html.twig',
            [
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getLogin(), $request->request->get('_token'))) {
            $this->utilisateurRepository->remove($utilisateur);
            $this->utilisateurRepository->flush();
        }

        return $this->redirectToRoute('grr_admin_user_index');
    }
}
