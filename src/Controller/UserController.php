<?php

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\UserType;
use App\Manager\GrrUserManager;
use App\Repository\GrrUtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/utilisateurs")
 */
class UserController extends AbstractController
{
    /**
     * @var GrrUtilisateurRepository
     */
    private $grrUtilisateurRepository;

    /**
     * @var GrrUserManager
     */
    private $grrUtilisateurManager;
    /**
     * @var UserFactory
     */
    private $userFactory;

    public function __construct(
        GrrUtilisateurRepository $grrUtilisateurRepository,
        UserFactory $userFactory,
        GrrUserManager $grrUtilisateurManager
    ) {
        $this->grrUtilisateurRepository = $grrUtilisateurRepository;
        $this->grrUtilisateurManager = $grrUtilisateurManager;
        $this->userFactory = $userFactory;
    }

    /**
     * @Route("/", name="grr_user_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            'grr_utilisateurs/index.html.twig',
            [
                'grr_utilisateurs' => $this->grrUtilisateurRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="grr_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrUtilisateur = $this->userFactory->createNew();
        $form = $this->createForm(UserType::class, $grrUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->grrUtilisateurRepository->insert($grrUtilisateur);

            return $this->redirectToRoute('grr_user_index');
        }

        return $this->render(
            'grr_utilisateurs/new.html.twig',
            [
                'grr_utilisateur' => $grrUtilisateur,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{login}", name="grr_user_show", methods={"GET"})
     */
    public function show(User $grrUtilisateur): Response
    {
        return $this->render(
            'grr_utilisateurs/show.html.twig',
            [
                'grr_utilisateur' => $grrUtilisateur,
            ]
        );
    }

    /**
     * @Route("/{login}/edit", name="grr_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $grrUtilisateur): Response
    {
        $form = $this->createForm(UserType::class, $grrUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->grrUtilisateurRepository->flush();

            return $this->redirectToRoute(
                'grr_user_index',
                [
                    'login' => $grrUtilisateur->getLogin(),
                ]
            );
        }

        return $this->render(
            'grr_utilisateurs/edit.html.twig',
            [
                'grr_utilisateur' => $grrUtilisateur,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{login}", name="grr_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $grrUtilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrUtilisateur->getLogin(), $request->request->get('_token'))) {
            $this->grrUtilisateurRepository->remove($grrUtilisateur);
            $this->grrUtilisateurRepository->flush();
        }

        return $this->redirectToRoute('grr_user_index');
    }
}
