<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerContactController extends AbstractController
{
    /**
     * @Route("/contact", name="controller_contact")
     */
    public function index(): Response
    {
        return $this->render('controller_contact/index.html.twig', [
            'controller_name' => 'ControllerContactController',
        ]);
    }
}
