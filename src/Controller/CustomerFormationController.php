<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerFormationController extends AbstractController
{
    /**
     * @Route("/customer/formation", name="customer_formation")
     */
    public function index(): Response
    {
        return $this->render('customer_formation/index.html.twig', [
            'controller_name' => 'CustomerFormationController',
        ]);
    }
}
