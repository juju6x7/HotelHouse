<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="show_contact", methods={"GET"})
     */
    public function showContact(): Response
    {
        return $this->render("contact.html.twig");
    }
}
