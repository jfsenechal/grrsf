<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\EntryType;
use App\Entity\TypeArea;
use App\Events\EntryTypeEvent;
use App\Factory\TypeAreaFactory;
use App\Factory\TypeEntryFactory;
use App\Form\TypeAssocAreaType;
use App\Form\TypeEntryType;
use App\Manager\TypeEntryManager;
use App\Repository\EntryTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/admin/type/area")
 * @IsGranted("ROLE_GRR_ADMINISTRATOR")
 */
class TypeAreaController extends AbstractController
{


    /**
     * @var TypeAreaFactory
     */
    private $typeAreaFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TypeAreaFactory $typeAreaFactory, EntityManagerInterface $entityManager)
    {
        $this->typeAreaFactory = $typeAreaFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/{id}/edit", name="grr_admin_type_area_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Area $area): Response
    {
        $assoc = $this->typeAreaFactory->createAreaAssoc($area);
        $form = $this->createForm(TypeAssocAreaType::class, $assoc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $types = $data->getTypes();

            foreach ($types as $type) {
                $typeArea = $this->typeAreaFactory->createNew($area, $type);
                $this->entityManager->persist($typeArea);
            }

            $this->entityManager->flush();

            /*   return $this->redirectToRoute(
                   'grr_admin_area_show',
                   [
                       'id' => $area->getId(),
                   ]
               );*/
        }

        return $this->render(
            '@grr_admin/type_area/edit.html.twig',
            [
                'area' => $area,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_type_area_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypeArea $typeArea): Response
    {
        $area = $typeArea->getArea();

        if ($this->isCsrfTokenValid('delete'.$typeArea->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($typeArea);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('grr_admin_area_show', ['id' => $area->getId()]);
    }
}
