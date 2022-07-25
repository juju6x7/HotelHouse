<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_home")
     */
    public function home(): Response
    {
        return $this->render('default/home.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
