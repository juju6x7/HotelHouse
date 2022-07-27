<?php

namespace App\Controller;

use DateTime;
use App\Entity\Slider;
use App\Form\SliderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SliderController extends AbstractController
{
    /**
     * @Route("/gestion-slider", name="show_slider", methods={"GET|POST"})
     */
    public function showSlider(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        try {
            $this->denyAccessUnLessGranted('ROLE_ADMIN');
        } catch (AccessDeniedException $exception) {
            $this->addFlash('warning', 'Cette partie du site est réservée aux admins');
            return $this->redirectToRoute('default_home');
        }

        $form = $this->createForm(SliderFormType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slider = new Slider();

            $slider->setCreatedAt(new DateTime());
            $slider->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);


                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $slider->setPhoto($newFilename);
                    $slider->setOrdre($form->get('ordre')->getData());
                } catch (FileException $exception) {
                    $this->addFlash('erreur', 'Votre photo n\'a pas été uploader');
                }
            } #end if photo

            $entityManager->persist($slider);
            $entityManager->flush();

            $this->addFlash('success', "Le slider est en ligne avec succès !");
            return $this->redirectToRoute('show_slider');
        }

        $sliders = $entityManager->getRepository(Slider::class)->findAll();
        return $this->render("admin/show_slider.html.twig", [
            'sliders' => $sliders,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ajouter-un-slider", name="create_slider", methods={"GET|POST"})
     */
    public function createSlider(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        dd('CETTE ACTION EST VIDE. voir le fichier : ' . __FILE__);
    } # end function createslide

    /**
     * @Route("voir_slider_{id}", name="show_slider_{id}", methods={"GET"})
     */
    public function showSliderId(Slider $slider): Response
    {
        return $this->render("admin/show_slider_id.html.twig", [
            'slider' => $slider
        ]);
    }

    /**
     * @Route("/modifier-slider_{id}", name="update_slider", methods={"GET|POST"})
     */
    public function updateSlider(Slider $slider, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        $form = $this->createForm(SliderFormType::class, $slider)->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $slider->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);


                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $slider->setPhoto($newFilename);
                    $slider->setOrdre($form->get('ordre')->getData());
                } catch (FileException $exception) {
                    $this->addFlash('erreur', 'Votre photo n\'a pas été uploader');
                }
            } #end if photo

        }

        $entityManager->persist($slider);
        $entityManager->flush();

        $sliders = $entityManager->getRepository(Slider::class)->findAll();

        return $this->render("admin/form/gestion_slider.html.twig", [
            'form' => $form->createView(),
            'sliders' => $sliders
        ]); 
        
        $this->addFlash('success', "Le slider a bien été modifié de la base de données");
        return $this->redirectToRoute('show_slider');
    } # end function updateSlider

    /**
     * @Route("/supprimer-slider_{id}", name="hard_delete_slider", methods={"GET"})
     */
    public function hardDeleteSlider(Slider $slider, EntityManagerInterface $entityManager): RedirectResponse
    {

        $entityManager->remove($slider);
        $entityManager->flush();

        $this->addFlash('success', "Le slider a bien été supprimé de la base de données");
        return $this->redirectToRoute('show_slider');
    }
}

//     /**
//      * @Route("/modifier-un-slide_{id}", name="update_slide", methods={"GET|POST"})
//      */
//     public function updateslide(slide $slide, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
//     {
//         $originalPhoto = $slide->getPhoto();

//         # 2 - Création du formulaire
//         $form = $this->createForm(slideFormType::class, $slide, [
//             'photo' => $originalPhoto
//         ])->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {


//             $slide->setCreatedAt(new DateTime());
//             $slide->setUpdatedAt(new DateTime());

//             # L'alias sera utilisé dans l'url (comme FranceTvInfo) et donc doit être assaini de tout accents et espaces.
//             $slide->setAlias($slugger->slug($slide->getTitle()));

//             /** @var UploadedFile $photo */
//             $photo = $form->get('photo')->getData();

//             # Si une photo a été uploadée dans le formulaire on va faire le traitement nécessaire à son stockage dans notre projet.
//             if ($photo) {

//                 # Déconstructioon
//                 $extension = '.' . $photo->guessExtension();
//                 $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
//                 $safeFilename = $slugger->slug($originalFilename);
//                 //                $safeFilename = $slide->getAlias();

//                 # Reconstruction
//                 $newFilename = $safeFilename . '_' . uniqid() . $extension;

//                 try {
//                     $photo->move($this->getParameter('uploads_dir'), $newFilename);
//                     $slide->setPhoto($newFilename);
//                 } catch (FileException $exception) {
//                     # Code à exécuter en cas d'erreur.
//                 }
//             } else {
//                 $slide->setPhoto($originalPhoto);
//             }

//             # Ajout d'un auteur à l'slide (User récupéré depuis la session)
//             $slide->setAuthor($this->getUser());

//             $entityManager->persist($slide);
//             $entityManager->flush();

//             $this->addFlash('success', "L'slide a été modifié avec succès !");
//             return $this->redirectToRoute('show_dashboard');
//         } # end if ($form)

//         # 3 - Création de la vue
//         return $this->render("admin/form/slide.html.twig", [
//             'form' => $form->createView(),
//             'slide' => $slide
//         ]);
//     } # end function updateslide

//     /**
//      * @Route("/archiver-un-slide_{id}", name="soft_delete_slide", methods={"GET"})
//      */
//     public function softDeleteslide(slide $slide, EntityManagerInterface $entityManager): Response
//     {
//         $slide->setDeletedAt(new DateTime());

//         $entityManager->persist($slide);
//         $entityManager->flush();

//         $this->addFlash('success', "L'slide a bien été archivé.");
//         return $this->redirectToRoute('show_dashboard');
//     } # end function soft delete

//     /**
//      * @Route ("/restaurer-un-slide_{id}", name="restore_slide", methods={"GET"})
//      */
//     public function restoreslide(slide $slide, EntityManagerInterface $entityManager): RedirectResponse
//     {
//         $slide->setDeletedAt(null);

//         $entityManager->persist($slide);
//         $entityManager->flush();

//         $this->addFlash('success', "L'slide a bien été restauré");
//         return $this->redirectToRoute('show_dashboard');
//     }

//     /**
//      * @Route("/voir-les-slides-archives", name="show_trash", methods={"GET"})
//      */
//     public function showTrash(EntityManagerInterface $entityManager): Response
//     {
//         $archivedslides = $entityManager->getRepository(slide::class)->findByTrash();

//         return $this->render("admin/trash/slide_trash.html.twig", [
//             'archivedslides' => $archivedslides
//         ]);
//     }

//     /**
//      * @Route("/supprimer-un-slide_{id}", name="hard_delete_slide", methods={"GET"})
//      */
//     public function hardDeleteslide(slide $slide, EntityManagerInterface $entityManager): RedirectResponse
//     {

//         // le hardelete ne supprime que la ligne dans la bdd, il n'a pas acces au photo qui sont sur le vscode qu'il faut supprimer nous meme avec la ligne de commande suivante

//         // suppression manuelle de la photo
//         $photo = $slide->getPhoto();

//         // on utilise la fonction native de PHP unLink() pour supprimer un fichier dans le filesystem(aussi appelé arborescence)
//         if ($photo) {
//             unLink($this->getParameter('uploads_dir') . '/' . $photo);
//         };


//         $entityManager->remove($slide);
//         $entityManager->flush();

//         $this->addFlash('success', "l'slide a bien été supprimé de la base de données");
//         return $this->redirectToRoute('show_trash');
//     }
// } # end class
