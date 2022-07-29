<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RetaurantController extends AbstractController
{
    /**
     * @Route("restaurant", name="show_restaurant", methods={"GET"})
     */
    public function showRestaurant(): Response
    {
        return $this->render("restaurant.html.twig");
    }
}
