<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Repository\CategoryRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FormFormationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="app_formation")
     */
    public function index(FormationRepository $repo): Response
    {
        $formations = $repo->findBy([]);
        
        return $this->render('modules/formation-list.html.twig', compact('formations'));
    }

    /**
     * @Route("/details/{id<[0-9]+>}/formation", name="app_formation_details")
     */
    public function details(FormationRepository $repo, Formation $formations): Response
    {
        $formations = $repo->find($formations);

        return $this->render('formation/details.html.twig', compact('formations'));
    }

    /**
     * @Route("/delete/{id<[0-9]+>}/formation", name="app_formation_delete")
     */
    public function delete(FormationRepository $repo, Formation $formations, EntityManagerInterface $em): Response
    {
        $formations = $repo->find($formations);
        $em->remove($formations);
        $em->flush();

        $this->addFlash('info', 'The Formation was be deleted succefuly');

        return $this->redirectToRoute('app_formation');
    }

    /** 
     * @Route("/create/formation", name="app_formation_create")
     */
    public function create(Request $request,CategoryRepository $repo,FormationRepository $rf, EntityManagerInterface $em): Response
    {
       $formations = new Formation;
       $categories = $repo->findBy([]);

       $form = $this->createForm(FormFormationType::class, $formations);
       $form->handleRequest($request);
       $category = $repo->findOneBy(array('name'=> $request->get('category')));

       if ($form->isSubmitted() && $form->isValid()) {

            $category = $repo->findOneBy(array('name'=> $request->get('category')));
            if ([] === $rf->findAll())
            {
               $code = substr(strtoupper($formations->getName()),0,3).'001';
            }
            else
            {
                $bout = substr(strtoupper($formations->getName()),0,3);
                $lastform = $rf->findBy([],['createdAt'=>'DESC']);
                $id = ($lastform[0]->getId() + 1);
                $length = 3;
                $string = substr(str_repeat(0, $length).$id, - $length);

                $code = $bout.$string;
            }   
            $formations->setCategory($category);
            $formations->setCode($code);
            $em->persist($formations);  
            // dd($formations);
            $em->flush();

            $this->addFlash('success' ,'The Formation was be created succefuly');

        return $this->redirectToRoute('app_formation');
       }
        $formulaire = $form->createView();
        return $this->render('formation/create.html.twig', compact('formulaire', 'categories'));
    }

    /**
     * @Route("/update/{id<[0-9]+>}/formation", name="app_formation_update")
     */
    public function update(FormationRepository $repo, Request $request, EntityManagerInterface $em, Formation $formations): Response
    {
       $formations = $repo->find($formations);
       $form = $this->createForm(FormFormationType::class, $formations);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) { 

        $em->flush();

        $this->addFlash('success', 'The Customer was be updated succefuly');

        return $this->redirectToRoute('app_formation');
       }
        $formulaire = $form->createView();
        return $this->render('formation/update.html.twig', compact('formulaire','formations'));
    }

}
