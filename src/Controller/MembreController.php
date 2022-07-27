<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreController extends AbstractController
{
    /**
     * @Route("/membre", name="register_membre", methods= {"GET|POST"})
     */
public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        # 1 - Instanciation de classe
        $membre = new Membre();

        # 2 - Création du formulaire
        $form = $this->createForm(RegisterFormType::class, $membre)
            ->handleRequest($request);

        # 3 - Si le form est soumis ET valide
        if($form->isSubmitted() && $form->isValid()) {
            $membre->setRoles(['ROLE_USER']);
            $membre->setCreatedAt(new DateTime());
            $membre->setUpdatedAt(new DateTime());

            $membre->setPassword($passwordHasher->hashPassword($membre, $membre->getPassword()));

            $entityManager->persist($membre);
            $entityManager->flush();

            $this->addFlash('success', "Vous vous êtes inscrit avec succès !");
            return $this->redirectToRoute('app_login');
        }

        # 4 - On retourne la vue du formulaire
        return $this->render("membre/register.html.twig", [
            'form' => $form->createView()
        ]);
        
    }

    // /**
    //  * @Route("/user/changer-mot-de-passe", name="change_password", methods={"GET|POST"})
    //  */
    // public function changePassword(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request): Response
    // {
    //     $form = $this->createForm(ChangePasswordFormType::class)->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

            
    //         $membre = $entityManager->getRepository(Membre::class)->findOneBy(['id' => $this->getUser()]);

    //         $membre->setUpdatedAt(new DateTime());

    //         $membre->setPassword($passwordHasher
    //         ->hashPassword($membre, 
    //         $form->get('plainPassword')->getData()

    //             )
    //         );

    //         $entityManager->persist($membre);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Votre mot de passe a été bien changé');
    //         return $this->redirectToRoute('show_membre');
    //     }

    //     return $this->render('user/change_password.html.twig', [
    //         'form' => $form->createView(),
            
    //     ]);
    // }
}

