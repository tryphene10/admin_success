<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FormCustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="app_customer")
     */
    public function index(CustomerRepository $repo): Response
    {
        $customers = $repo->findAll();
        return $this->render('customer/index.html.twig', compact('customers'));
    }

    /**
     * @Route("/details/{id<[0-9]+>}/customer", name="app_customer_details")
     */
    public function details(CustomerRepository $repo, Customer $customers): Response
    {
        $customers = $repo->find($customers);

        return $this->render('customer/details.html.twig', compact('customers'));
    }

    /**
     * @Route("/delete/{id<[0-9]+>}/customer", name="app_customer_delete")
     */
    public function delete(CustomerRepository $repo, Customer $customers, EntityManagerInterface $em): Response
    {
        $customers = $repo->find($customers);
        $em->remove($customers);
        $em->flush();

        $this->addFlash('info', 'The Customer was be deleted succefuly');

        return $this->redirectToRoute('app_customer');
    }

    /** 
     * @Route("/create/customer", name="app_customer_create")
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $repo): Response
    {
       $customers = new Customer;
       $user = $repo->findAll();

       $form = $this->createForm(FormCustomerType::class, $customers);
       $form->handleRequest($request);
       
       if ($form->isSubmitted() && $form->isValid()) {

            $customers->setUsers($user[0]);
            $em->persist($customers);   
            $em->flush();

            $this->addFlash('success' ,'The Customer was be created succefuly');

        return $this->redirectToRoute('app_customer');
       }
        $formulaire = $form->createView();
        return $this->render('customer/create.html.twig', compact('formulaire'));
    }

    /**
     * @Route("/update/{id<[0-9]+>}/customer", name="app_customer_update")
     */
    public function update(CustomerRepository $repo, Request $request, EntityManagerInterface $em, Customer $customers): Response
    {
       $customers = $repo->find($customers);
       $form = $this->createForm(FormCustomerType::class, $customers);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) { 

        $em->flush();

        $this->addFlash('success', 'The Customer was be updated succefuly');

        return $this->redirectToRoute('app_customer');
       }
        $formulaire = $form->createView();
        return $this->render('customer/update.html.twig', compact('formulaire','customers'));
    }
}
