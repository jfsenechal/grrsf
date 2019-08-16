<?php

namespace App\Controller\Admin;

use App\Entity\Security\User;
use App\Form\UserPasswordType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/password")
 */
class PasswordController extends AbstractController
{
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @Route("/{id}", name="grr_admin_user_password")
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $password = $data->getPassword();
            $user->setPassword($this->userManager->encodePassword($user, $password));
            $this->userManager->flush();
        }

        return $this->render(
            '@grr_admin/utilisateurs/edit_password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
