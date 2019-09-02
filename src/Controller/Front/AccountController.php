<?php

namespace App\Controller\Front;

use App\Form\Security\UserType;
use App\Form\Security\UserPasswordType;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account")
 * @IsGranted("ROLE_GRR")
 */
class AccountController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userManager = $userManager;
    }

    /**
     * @Route("/show", name="grr_account_show", methods={"GET"})
     */
    public function show(): Response
    {
        $user = $this->getUser();

        return $this->render(
            '@grr_front/account/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/edit", name="grr_account_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->flush();

            return $this->redirectToRoute('grr_account_show');
        }

        return $this->render(
            '@grr_front/account/edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/password", name="grr_account_edit_password", methods={"GET", "POST"})
     */
    public function password(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $password = $data->getPassword();

            $user->setPassword($this->userManager->encodePassword($user, $password));

            $this->userManager->flush();

            return $this->redirectToRoute('grr_account_show');
        }

        return $this->render(
            '@grr_front/account/edit_password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/delete", name="grr_user_account_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userManager->remove($user);
            $this->userManager->flush();
        }

        return $this->redirectToRoute('grr_home');
    }
}
