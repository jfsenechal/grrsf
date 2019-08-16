<?php

namespace App\Controller;

use App\Form\Security\UserEditType;
use App\Form\UserPasswordType;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 * @IsGranted("ROLE_GRR")
 */
class UserController extends AbstractController
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
     * @Route("/profile", name="grr_user_show", methods={"GET"})
     */
    public function show(): Response
    {
        $user = $this->getUser();

        return $this->render(
            'user/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/edit", name="grr_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userManager->flush();

            return $this->redirectToRoute('grr_user_show');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/password", name="grr_user_edit_password", methods={"GET","POST"})
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

            return $this->redirectToRoute('grr_user_show');
        }

        return $this->render(
            'user/edit_password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/delete", name="grr_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            $this->userManager->remove($user);
            $this->userManager->flush();
        }

        return $this->redirectToRoute('grr_front_home');
    }
}
