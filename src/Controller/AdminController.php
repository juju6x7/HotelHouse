<?php

namespace App\Controller;

use App\Entity\Membre;
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
}
