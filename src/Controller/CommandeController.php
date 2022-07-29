<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\ReservationsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande-chambre", name="commande_chambre", methods={"GET|POST"})
     */
    public function reservationChambre(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();

        $form = $this->createForm(ReservationsFormType::class, $commande)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $commande->setCreatedAt(new DateTime());
            $commande->setUpdatedAt(new DateTime());

                $entityManager->persist($commande);
                $entityManager->flush();

                $this->addFlash('success', "La commande a bien été validée !");
                return $this->redirectToRoute('dafault_home');

        } # end if ($form)

        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);
        
        return $this->render("reservation/commande_chambre.html.twig", [
            'form' => $form->createView(),
            'chambres' => $chambres
        ]);
    } # end function createChambre
}
