<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CochsController extends AbstractController
{
    /**
     * @Route("/cochs", name="cochs")
     */
    public function index(): Response
    {
        return $this->render('cochs/index.html.twig', [
            'controller_name' => 'CochsController',
        ]);
    }
}
