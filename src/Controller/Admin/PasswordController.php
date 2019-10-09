<?php

namespace App\Controller\Admin;

use App\Entity\Security\User;
use App\Events\UserEvent;
use App\Form\Security\UserPasswordType;
use App\Manager\UserManager;
use App\Security\PasswordHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/admin/password")
 * @IsGranted("ROLE_GRR_MANAGER_USER")
 */
class PasswordController extends AbstractController
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var PasswordHelper
     */
    private $passwordEncoder;

    public function __construct(
        UserManager $userManager,
        PasswordHelper $passwordEncoder,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/{id}", name="grr_admin_user_password")
     */
    public function edit(Request $request, UserInterface $user)
    {
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $password = $data->getPassword();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $this->userManager->flush();

            $userEvent = new UserEvent($user);
            $this->eventDispatcher->dispatch($userEvent, UserEvent::CHANGE_PASSWORD_SUCCESS);

            return $this->redirectToRoute(
                'grr_admin_user_show',
                ['id' => $user->getId()]
            );
        }

        return $this->render(
            '@grr_admin/user/edit_password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
