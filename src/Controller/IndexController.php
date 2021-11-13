<?php

namespace App\Controller;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FormationRepository;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
    */
    public function index(FormationRepository $repo): Response
    {
        $formations = $repo->findAll();
        //dd($formations);
        return $this->render('user-interface/index.html.twig', compact('formations'));
    }

    /**
     * @Route("/about", name="about")
    */
    public function about(): Response
    {
        return $this->render('user-interface/page/services/about.html.twig');
    }

    /**
     * @Route("/service", name="service")
    */
    public function services(): Response
    {
        return $this->render('user-interface/page/services/service.html.twig');
    }

     /**
     * @Route("/formation/categorie", name="formationCategorie")
     */
    public function formations(): Response
    {
        return $this->render('user-interface/page/services/formation.html.twig');
    }

}
