<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Security\User;
use App\Form\Security\AuthorizationAreaType;
use App\Handler\HandlerAuthorizationArea;
use App\Model\AuthorizationAreaModel;
use App\Repository\Security\AuthorizationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/authorization/user")
 */
class AuthorizationController extends AbstractController
{
    /**
     * @var HandlerAuthorizationArea
     */
    private $handlerAuthorizationArea;
    /**
     * @var AuthorizationRepository
     */
    private $userAuthorizationRepository;

    public function __construct(
        HandlerAuthorizationArea $handlerUserManagerArea,
        AuthorizationRepository $userAuthorizationRepository
    ) {
        $this->handlerAuthorizationArea = $handlerUserManagerArea;
        $this->userAuthorizationRepository = $userAuthorizationRepository;
    }


    /**
     * @Route("/{id}", name="grr_authorization_show_by_user", methods={"GET"})
     */
    public function show(User $user): Response
    {
        $authorizations = $this->userAuthorizationRepository->findBy(['user' => $user]);

        return $this->render(
            'security/authorization/show.html.twig',
            [
                'user' => $user,
                'authorizations' => $authorizations,
            ]
        );
    }

    /**
     * @Route("/delete", name="grr_user_authorization_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $id = $request->get('idauth');
        $token = $request->get('_tokenauth');

        $userAuthorization = $this->userAuthorizationRepository->find($id);

        if (!$userAuthorization) {
            $this->createNotFoundException();
        }

        $area = $userAuthorization->getArea();

        if ($this->isCsrfTokenValid('delete'.$userAuthorization->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userAuthorization);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grr_authorization_area_show', ['id' => $area->getId()]);
    }
}
