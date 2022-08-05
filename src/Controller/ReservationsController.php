<?php

namespace App\Controller;

use App\Entity\Chambre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationsController extends AbstractController
{
    /**
    * @Route("/voir-chambres-reservation", name="show_reservation", methods={"GET"})
    */
    public function showReservation(EntityManagerInterface $entityManager): Response
    {
        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);
        
        return $this->render('reservation/show_reservation.html.twig', [
            'chambres' => $chambres
        ]);
    } # end function Reservation()

    /**
     * @Route("voir-chambre-reservation{id}", name="show_reservation_chambre", methods={"GET|POST"})
     */
    public function showReservationChambre(Chambre $chambre): Response
    {
        return $this->render("admin/show_chambre_id.html.twig", [
            'chambre' => $chambre
        ]);
    }

    
}
//     /**
//      * @Route("/modifier-chambre_{id}", name="update_chambre", methods={"GET|POST"})
//      */
//     public function updateChambre(Chambre $chambre, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
//     {
//         $originalPhoto = $chambre->getPhoto();

//         $form = $this->createForm(ChambreFormType::class, $chambre, [
//             'photo' => $originalPhoto
//         ])->handleRequest($request);

//         if($form->isSubmitted() && $form->isValid()) {

//             $chambre->setUpdatedAt(new DateTime());

//             // # L'alias sera utilisé dans l'url (comme FranceTvInfo) et donc doit être assaini de tout accents et espaces.
//             // $chambre->setAlias($slugger->slug($chambre->getTitle()));

//             /** @var UploadedFile $photo */
//             $photo = $form->get('photo')->getData();

//             if($photo) {
//                 $extension = '.' . $photo->guessExtension();
//                 $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
//                 $safeFilename = $slugger->slug($originalFilename);
// //                $safeFilename = $chambre->getAlias();

//                 $newFilename = $safeFilename . '_' . uniqid() . $extension;

//                 try {
//                     $photo->move($this->getParameter('uploads_dir'), $newFilename);
//                     $chambre->setPhoto($newFilename);
//                 }
//                 catch(FileException $exception) {
//                     # Code à exécuter en cas d'erreur.
//                 }
//             } else {
//                 $chambre->setPhoto($originalPhoto);
//             } # end if($photo)

//             $entityManager->persist($chambre);
//             $entityManager->flush();

//             $this->addFlash('success', "La chambre a bien été modifiée !");
//             return $this->redirectToRoute('show_chambres');
//         } # end if ($form)

//         return $this->render("admin/form/gestion_chambre.html.twig", [
//             'form' => $form->createView(),
//             'chambre' => $chambre
//         ]);
//     }# end function updateChambre

//     /**
//      * @Route("/supprimer-chambre_{id}", name="hard_delete_chambre", methods={"GET"})
//      */
//     public function hardDeleteChambre(Chambre $chambre, EntityManagerInterface $entityManager): RedirectResponse
//     {
//         $photo = $chambre->getPhoto();

//         unlink($this->getParameter('uploads_dir'). '/' . $photo);

//         $entityManager->remove($chambre);
//         $entityManager->flush();

//         $this->addFlash('success', "La chambre a bien été supprimée de la base de données.");
//         return $this->redirectToRoute('show_chambres');
//     } #end admin chambre
// } # end class
