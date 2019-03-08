<?php

namespace App\Controller;

use App\Entity\GrrSetting;
use App\Form\GrrSettingType;
use App\Repository\GrrSettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/setting")
 */
class GrrSettingController extends AbstractController
{
    public function __construct(GrrSettingRepository $grrSettingRepository)
    {

    }

    /**
     * @Route("/", name="grr_setting_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('grr_setting/index.html.twig', [
            'grr_settings' => $grrSettingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="grr_setting_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrSetting = new GrrSetting();
        $form = $this->createForm(GrrSettingType::class, $grrSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grrSetting);
            $entityManager->flush();

            return $this->redirectToRoute('grr_setting_index');
        }

        return $this->render('grr_setting/new.html.twig', [
            'grr_setting' => $grrSetting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_setting_show", methods={"GET"})
     */
    public function show(GrrSetting $grrSetting): Response
    {
        return $this->render('grr_setting/show.html.twig', [
            'grr_setting' => $grrSetting,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="grr_setting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrSetting $grrSetting): Response
    {
        $form = $this->createForm(GrrSettingType::class, $grrSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('grr_setting_index', [
                'name' => $grrSetting->getName(),
            ]);
        }

        return $this->render('grr_setting/edit.html.twig', [
            'grr_setting' => $grrSetting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}", name="grr_setting_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrSetting $grrSetting): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrSetting->getName(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grrSetting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grr_setting_index');
    }
}
