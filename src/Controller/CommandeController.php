<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    /**
     * @Route("/modifier-commande_{id}", name="update_commande", methods = {"GET|POST"})
     */
    public function showMembre(Commande $commande, Request $request, EntityManagerInterface $entityManager): Response
    {        
             $form = $this->createForm(CommandeFormType::class, $commande)->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $commande->setUpdatedAt(new DateTime());

        }

        $entityManager->persist($commande);
        $entityManager->flush();

       
        $this->addFlash('success', "La commande a été modifiée avec succès !");
        // return $this->redirectToRoute('show_commande');

        $commandes = $entityManager->getRepository(Membre::class)->findAll();

        return $this->render("admin/form/gestion_commandes.html.twig", [
            'form' => $form->createView(),
            'membres' => $commandes
        ]); 
    } # end function updatemembre
}