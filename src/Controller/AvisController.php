<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="show_avis", methods={"GET"})
     */
    public function showAvis(): Response
    {
        return $this->render('avis.html.twig');
    }
}

