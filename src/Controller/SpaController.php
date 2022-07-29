<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpaController extends AbstractController
{
    /**
     * @Route("/spa", name="show_spa", methods={"GET"})
     */
    public function showSpa(): Response
    {
        return $this->render("spa.html.twig");
    }
}
