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
    *@Route("/voir-commandes", name="show_commande", methods={"GET"})
    */
    public function showCommande(EntityManagerInterface $entityManager): Response
    {
    $commandes = $entityManager->getRepository(Commande::class)->findAll();
    return $this->render("/commande/show_commande.html.twig", [
    'commandes' => $commandes
    ]);
    }

    /**
     * @Route("/ajouter-une-commande", name="create_commande", methods={"GET|POST"})
     */
    public function createCommande(Request $request, EntityManagerInterface $entityManager): Response
    {
        # 1 - Instanciation
        $commande = new Commande();

        # 2 - Création du formulaire
        $form = $this->createForm(CommandeFormType::class, $commande)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $commande->setCreatedAt(new DateTime());
            $commande->setUpdatedAt(new DateTime());

            #3 Ajout des data de commande
             $commande->getId();
             $commande->getFirstname();
             $commande->getChambre();
             $commande->getChambre();
             $commande->getDateArrival();
             $commande->getDateDeparture();
             $commande->getFirstname();
             $commande->getLastname();
             $commande->getPhone();
             $commande->getEmail();
             

                $entityManager->persist($commande);
                $entityManager->flush();

                $this->addFlash('success', "Votre commande Administrateur a bien été ajoutée.");
                return $this->redirectToRoute('show_commande');

        } # end if ($form)

        # 4 - Création de la vue
        return $this->render("commande\show_commande.html.twig", [
            'form' => $form->createView(),
    
        ]);
    } # end function createCommande


    /**
     * @Route("/modifier-commande_{id}", name="update_commande", methods = {"GET|POST"})
     */
    public function updateCommande(Commande $commande, Request $request, EntityManagerInterface $entityManager): Response
    {        
             $form = $this->createForm(CommandeFormType::class, $commande)->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
    {
      
            $commande->setUpdatedAt(new DateTime());

        $entityManager->persist($commande);
        $entityManager->flush();

       
        $this->addFlash('success', "La commande a été modifiée avec succès !");
        return $this->redirectToRoute('show_commande');
        }
        $commande = $entityManager->getRepository(Commande::class)->findById();

        return $this->render("admin/form/gestion_commandes.html.twig", [
            'form' => $form->createView(),
            'commande' => $commande
        ]); 
    } # end funct


     /**
     * @Route("/archiver-commande_{id}", name="soft_delete_commande", methods={"GET"})
     */
    public function archiverCommande(Commande $commande, EntityManagerInterface $entityManager):  Response
    {     
        $commande->setDeletedAt(new DateTime());
        $commande->setUpdatedAt(new DateTime());

            $entityManager->persist($commande);
            $entityManager->flush();

        $this->addFlash('success', "La commande a bien été archivée");
        return $this->redirectToRoute("show_commande"); 

}

 /**
     * @Route("/restaurer-une-commande_{id}", name="restore_commande", methods={"GET"})
     */
    public function restoreCommande(Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $commande->setDeletedAt(null);

        $entityManager->persist($commande);
        $entityManager->flush();

        $this->addFlash('success', "La commande a bien été restaurée");
        return $this->redirectToRoute('show_commande');
    }

    /**
     * @Route("/voir-les-commandes-archivees_{id}", name="show_trash", methods={"GET"})
     */
    public function showTrash(EntityManagerInterface $entityManager): Response
    {
        $archivedCommandes = $entityManager->getRepository(Commande::class)->findByTrash();

        return $this->render("admin/trash/commande_trash.html.twig", [
            'archivedCommandes' => $archivedCommandes,
        ]);
    }


}







