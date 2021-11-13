<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FormInvoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{

    /**
     * @Route("/invoice", name="app_invoice")
     */
    public function index(InvoiceRepository $repo): Response
    {
        $invoices = $repo->findAll();

        return $this->render('modules/invoice-list.html.twig', compact('invoices'));
    }

    /**
     * @Route("/details/{id<[0-9]+>}/invoice", name="app_invoice_details")
     */
    public function details(InvoiceRepository $repo, Invoice $invoices): Response
    {
        $invoices = $repo->find($invoices);

        return $this->render('invoice/details.html.twig', compact('invoices'));
    }

    /**
     * @Route("/delete/{id<[0-9]+>}/invoice", name="app_invoice_delete")
     */
    public function delete(InvoiceRepository $repo, Invoice $invoices, EntityManagerInterface $em): Response
    {
        $invoices = $repo->find($invoices);
        $em->remove($invoices);
        $em->flush();

        $this->addFlash('info', 'The Invoice was be deleted succefuly');

        return $this->redirectToRoute('app_invoice');
    }

    /** 
     * @Route("/create/invoice", name="app_invoice_create")
     */
    public function create(Request $request,CustomerRepository $repo,InvoiceRepository $rf, EntityManagerInterface $em): Response
    {
       $invoices = new Invoice;
       $customers = $repo->findBy([]);

       $form = $this->createForm(FormInvoiceType::class, $invoices);
       $form->handleRequest($request);
       $customer = $repo->findOneBy(array('name'=> $request->get('customer')));

       if ($form->isSubmitted() && $form->isValid()) {

            $customer = $repo->findOneBy(array('name'=> $request->get('customer')));

            if ([] === $rf->findAll())
            {
                $year = date('y');
                $bout = substr(strtoupper('Facture'),0,4);
                $reference = $year.$bout.'001';

            }
            else
            {
                $year = date('y');
                $bout = substr(strtoupper('Facture'),0,4);
                $lastform = $rf->findBy([],['createdAt'=>'DESC']);
                $id = ($lastform[0]->getId() + 1);
                $length = 3;
                $string = substr(str_repeat(0, $length).$id, - $length);

                $reference = $year.$bout.$string;


            } 
            $invoices->setReference($reference);
            $invoices->setCustomer($customer);

            $em->persist($invoices);

            $em->flush();

            $this->addFlash('success' ,'The invoice was be created succefuly');

        return $this->redirectToRoute('app_invoice');
       }

        return $this->render('modules/invoice-add.html.twig');
    }

    /**
     * @Route("/update/{id<[0-9]+>}/invoice", name="app_invoice_update")
     */
    public function update(InvoiceRepository $repo, Request $request, EntityManagerInterface $em, Invoice $invoices): Response
    {
       $invoices = $repo->find($invoices);
       $form = $this->createForm(FormInvoiceType::class, $invoices);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) { 

        $em->flush();

        $this->addFlash('success', 'The Invoice was be updated succefuly');

        return $this->redirectToRoute('app_invoice');
       }
        $formulaire = $form->createView();
        return $this->render('invoice/update.html.twig', compact('formulaire','invoices'));
    }
}
