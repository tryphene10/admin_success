<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Repository\CategoryRepository;
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
     * @Route("/catalogues-des-formations-de-success-consults", name="formationCategorie")
     */
    public function formations(CategoryRepository $repo): Response
    {
        $categorie = $repo->findAll();
        return $this->render('user-interface/page/services/formation.html.twig');
    }
    
    /**
     * @Route("/demande-un-devis", name="devit")
    */
    public function devitFormation(): Response
    {
        
        return $this->render('user-interface/page/devit/devit.html.twig');
    }

}
