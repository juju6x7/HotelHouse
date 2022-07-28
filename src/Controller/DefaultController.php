<?php

namespace App\Controller;

use App\Entity\Slider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_home", methods={"GET"})
     */
    public function home(EntityManagerInterface $entityManager): Response
    {
        #EXERCICE : Récupérer les articles non archivés et envoyer les à la vue twig 
        // $articles = $entityManager->getRepository(Article::class)->findBy(['deletedAt'=> null]);
       $sliders = $entityManager->getRepository(Slider::class)->findAll();

        return $this->render("default/home.html.twig", [
            'sliders'=>$sliders
        ]);
    }
}
