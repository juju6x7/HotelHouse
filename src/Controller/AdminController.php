<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Form\MembreFormType;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/voir-membre", name="show_membre", methods={"GET"})
     */
    public function showMembre(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $this->denyAccessUnLessGranted('ROLE_ADMIN');
        } catch (AccessDeniedException $exception) {
            $this->addFlash('warning', 'Cette partie du site est réservée aux admins');
            return $this->redirectToRoute('default_home');
        }

        $membres = $entityManager->getRepository(Membre::class)->findAll();
        return $this->render("admin/show_membre.html.twig", [
            'membres' => $membres,
        ]);
    }


    /**
     * @Route("/modifier-membre_{id}", name="update_membre", methods={"GET|POST"})
     */
    public function updateMembre(Membre $membre, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(MembreFormType::class, $membre)->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $membre->setUpdatedAt(new DateTime());
            // $membre->getId();
        }

        $entityManager->persist($membre);
        $entityManager->flush();

        $this->addFlash('success', "Le membre a été modifié avec succès !");

        $membres = $entityManager->getRepository(Membre::class)->findAll();
        return $this->render("admin/form/gestion_membre.html.twig", [
            'form' => $form->createView(),
            'membres' => $membres
        ]);

        // $membre = $entityManager->getRepository(Membre::class)->findBy(['membre' => $membre->getId()]);
    } # end function updatemembre
}
