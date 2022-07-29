<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    /**
     * @Route("/histoire-hotel", name="show_hotel", methods={"GET"})
     */
    public function showHotel(): Response
    {
        return $this->render("hotel.html.twig");
    }
}
