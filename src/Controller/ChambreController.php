<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Form\ChambreFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ChambreController extends AbstractController
{
    /**
    * @Route("/voir-chambres", name="show_chambres", methods={"GET"})
    */
    public function showChambre(EntityManagerInterface $entityManager): Response
    {
        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);
        
        return $this->render('admin/show_chambres.html.twig', [
            'chambres' => $chambres
        ]);
    } # end function showChambre()

    
    /**
     * @Route("/ajouter-chambre", name="create_chambre", methods={"GET|POST"})
     */
    public function createChambre(Chambre $chambre, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        # 1 - Instanciation
        $chambre = new Chambre();

        # 2 - Création du formulaire
        $form = $this->createForm(ChambreFormType::class, $chambre)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $chambre->setCreatedAt(new DateTime());
            $chambre->setUpdatedAt(new DateTime());

            // # L'alias sera utilisé dans l'url (comme FranceTvInfo) et donc doit être assaini de tout accents et espaces.
            // $chambre->setAlias($slugger->slug($chambre->getTitle()));

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            # Si une photo a été uploadée dans le formulaire on va faire le traitement nécessaire à son stockage dans notre projet.
            if($photo) {
                # Déconstructioon
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
//                $safeFilename = $chambre->getAlias();

                # Reconstruction
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $chambre->setPhoto($newFilename);
                }
                catch(FileException $exception) {
                    # Code à exécuter en cas d'erreur.
                }
            } # end if($photo)

                // # Ajout d'un auteur à la chambre (User récupéré depuis la session)
                // $chambre->setAuthor($this->getUser());

                $entityManager->persist($chambre);
                $entityManager->flush();

                $this->addFlash('success', "La chambre a bien été mise en ligne !");
                return $this->redirectToRoute('admin/show_chambres.html.twig');

        } # end if ($form)

        # 3 - Création de la vue
        return $this->render("admin/form/gestion_chambre.html.twig", [
            'form' => $form->createView()
        ]);
    } # end function createChambre

    /**
     * @Route("/modifier-chambre_{id}", name="update_chambre", methods={"GET|POST"})
     */
    public function updateChambre(Chambre $chambre, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $originalPhoto = $chambre->getPhoto();

        # 2 - Création du formulaire
        $form = $this->createForm(ChambreFormType::class, $chambre, [
            'photo' => $originalPhoto
        ])->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $chambre->setUpdatedAt(new DateTime());

            // # L'alias sera utilisé dans l'url (comme FranceTvInfo) et donc doit être assaini de tout accents et espaces.
            // $chambre->setAlias($slugger->slug($chambre->getTitle()));

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            # Si une photo a été uploadée dans le formulaire on va faire le traitement nécessaire à son stockage dans notre projet.
            if($photo) {

                # Déconstructioon
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
//                $safeFilename = $chambre->getAlias();

                # Reconstruction
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $chambre->setPhoto($newFilename);
                }
                catch(FileException $exception) {
                    # Code à exécuter en cas d'erreur.
                }
            } else {
                $chambre->setPhoto($originalPhoto);
            } # end if($photo)

            // # Ajout d'un auteur à l'chambre (User récupéré depuis la session)
            // $chambre->setAuthor($this->getUser());

            $entityManager->persist($chambre);
            $entityManager->flush();

            $this->addFlash('success', "La chambre a bien été modifiée !");
            return $this->redirectToRoute('admin/show_chambres.html.twig');
        } # end if ($form)

        # 3 - Création de la vue
        return $this->render("admin/form/gestion_chambre.html.twig", [
            'form' => $form->createView(),
            'chambre' => $chambre
        ]);
    }# end function updateChambre

    /**
     * @Route("/archiver-chambre_{id}", name="soft_delete_chambre", methods={"GET"})
     */
    public function softDeleteChambre(Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        $chambre->setDeletedAt(new DateTime());

        $entityManager->persist($chambre);
        $entityManager->flush();

        $this->addFlash('success', "La chambre a bien été archivée.");
        return $this->redirectToRoute('admin/show_chambres.html.twig');
    }# end function softDelete

    /**
     * @Route("/restaurer-chambre_{id}", name="restore_chambre", methods={"GET"})
     */
    public function restoreChambre(Chambre $chambre, EntityManagerInterface $entityManager): RedirectResponse
    {
        $chambre->setDeletedAt(null);

        $entityManager->persist($chambre);
        $entityManager->flush();

        $this->addFlash('success', "La chambre a bien été restaurée.");
        return $this->redirectToRoute('admin/show_chambres.html.twig');
    }

    /**
     * @Route("/voir-les-chambres-archives", name="show_trash", methods={"GET"})
     */
    public function showTrash(EntityManagerInterface $entityManager): Response
    {
        $archivedChambres = $entityManager->getRepository(Chambre::class)->findByTrash();

        return $this->render("admin/trash/chambre_trash.html.twig", [
            'archivedChambres' => $archivedChambres
        ]);
    }

    /**
     * @Route("/supprimer-chambre_{id}", name="hard_delete_chambre", methods={"GET"})
     */
    public function hardDeleteChambre(Chambre $chambre, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Suppression manuelle de la photo.
        $photo = $chambre->getPhoto();
        
        // On utilise la fonction native de PHP unlink() pour supprimer un fichier dans le filesystem.
        unlink($this->getParameter('uploads_dir'). '/' . $photo);

        $entityManager->remove($chambre);
        $entityManager->flush();

        $this->addFlash('success', "La chambre a bien été supprimée de la base de données.");
        return $this->redirectToRoute('admin/show_chambres.html.twig');
    } #end admin chambre
} # end class