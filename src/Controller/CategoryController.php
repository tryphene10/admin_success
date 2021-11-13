<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FormCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/details/{id<[0-9]+>}/category", name="app_category_details")
     */
    public function details(CategoryRepository $repo, Category $category): Response
    {
        $categoy = $repo->find($category);

        return $this->render('category/details.html.twig', compact('category'));
    }

    /**
     * @Route("/delete/{id<[0-9]+>}/category", name="app_category_delete")
     */
    public function delete(CategoryRepository $repo, Category $category, EntityManagerInterface $em): Response
    {
        $category = $repo->find($category);
        $em->remove($category);
        $em->flush();

        $this->addFlash('info', 'The Apareil was be deleted succefuly');

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/create/category", name="app_category_create")
     */
    public function create(Request $request,CategoryRepository $catrepo, EntityManagerInterface $em): Response
    {
       $category = new Category;
       $categories = $catrepo->findBy([]);
       $form = $this->createForm(FormCategoryType::class, $category);

       $form->handleRequest($request);
       if ( $form->isSubmitted() && $form->isValid() ){

        $em->persist($category);   
        $em->flush();

        $this->addFlash('success' ,'The Apareil was be created succefuly');

        return $this->redirectToRoute('app_dashboard');
       }
       $formulaire = $form->createView();


        return $this->render('modules/form-category.html.twig', compact('formulaire','categories'));
    }

    /**
     * @Route("/update/{id<[0-9]+>}/category", name="app_category_update")
     */
    public function update(CategoryRepository $repo, Request $request, EntityManagerInterface $em, Category $category): Response
    {
       $category = $repo->find($category);
       $form = $this->createForm(FormCategoryType::class, $category);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) { 

        $em->flush();

        $this->addFlash('success', 'The Apareil was be updated succefuly');

        return $this->redirectToRoute('app_home');
       }
        $formulaire = $form->createView();
        return $this->render('category/update.html.twig', compact('formulaire','category'));
    }
}
