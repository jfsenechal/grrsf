<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Form\SettingType;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/setting")
 */
class SettingController extends AbstractController
{
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @Route("/", name="grr_setting_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            'setting/index.html.twig',
            [
                'settings' => $this->settingRepository->findAll(),
            ]
        );
    }

 /**
     * @Route("/new", name="grr_setting_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $setting = new Setting();
        $form = $this->createForm(SettingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($setting);
            $entityManager->flush();

            return $this->redirectToRoute('grr_setting_index');
        }

        return $this->render('setting/new.html.twig', [
            'setting' => $setting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_setting_show", methods={"GET"})
     */
    public function show(Setting $setting): Response
    {
        return $this->render(
            'setting/show.html.twig',
            [
                'setting' => $setting,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_setting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Setting $setting): Response
    {
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->settingRepository->flush();

            return $this->redirectToRoute(
                'grr_setting_index',
                [
                    'name' => $setting->getName(),
                ]
            );
        }

        return $this->render(
            'setting/edit.html.twig',
            [
                'setting' => $setting,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{name}", name="grr_setting_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Setting $setting): Response
    {
        if ($this->isCsrfTokenValid('delete'.$setting->getName(), $request->request->get('_token'))) {
            $this->settingRepository->remove($setting);
            $this->settingRepository->flush();
        }

        return $this->redirectToRoute('grr_setting_index');
    }
}
