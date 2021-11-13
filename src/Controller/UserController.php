<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use App\Repository\CustomerRepository;
use App\Repository\FormationRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FormUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {

        return $this->render('modules/login-with-bg.html.twig');
    }
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(CategoryRepository $catrepo,
                            CustomerRepository $cusrepo,
                            FormationRepository $forrepo,
                            InvoiceRepository $invrepo): Response
    {
        $categories = $catrepo->findBy([]);
        $customers = $cusrepo->findBy([]);
        $formations = $forrepo->findBy([]);
        $invoices = $invrepo->findBy([]);

        return $this->render('modules/index.html.twig', compact('categories','customers','formations','invoices'));
    }

    /**
     * @Route("/user-connect", name="app_user_connect")
     */
    public function connect(UserRepository $repo,
                             Request $request,
                             CategoryRepository $catrepo,
                             CustomerRepository $cusrepo,
                             FormationRepository $forrepo,
                             InvoiceRepository $invrepo): Response
    {

        $categories = $catrepo->findBy([]);
        $customers = $cusrepo->findBy([]);
        $formations = $forrepo->findBy([]);
        $invoices = $invrepo->findBy([]);
       

        if (null !=$repo->findOneBy(array('username'=> $request->get('username')))) {

            return $this->render('modules/index.html.twig', compact('categories','customers','formations','invoices'));
        }

        return $this->render('modules/login-with-bg.html.twig');
    }

    /**
     * @Route("/details/{id<[0-9]+>}/user", name="app_user_details")
     */
    public function details(UserRepository $repo, User $users): Response
    {
        $users = $repo->find($users);

        return $this->render('user/details.html.twig', compact('users'));
    }

    /**
     * @Route("/delete/{id<[0-9]+>}/user", name="app_user_delete")
     */
    public function delete(UserRepository $repo, User $user, EntityManagerInterface $em): Response
    {
        $user = $repo->find($user);
        $em->remove($user);
        $em->flush();

        $this->addFlash('info', 'The user was be deleted succefuly');

        return $this->redirectToRoute('app_user');
    }

    /**
     * @Route("/create/user", name="app_user_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        
       $user = new User;
       $form = $this->createForm(FormUserType::class, $user);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {

        $password = base64_encode(uniqid().$user->getPassword());
        
        $user->setUserName('Admin');

        $user->setPassword($password);

        $em->persist($user);   
        $em->flush();

        $this->addFlash('success' ,'The User was be created succefuly');

        return $this->redirectToRoute('app_user');
       }
        $formulaire = $form->createView();
        return $this->render('user/create.html.twig', compact('formulaire'));
    }

    /**
     * @Route("/update/{id<[0-9]+>}/user", name="app_user_update")
     */
    public function update(UserRepository $repo, Request $request, EntityManagerInterface $em, User $users): Response
    {
       $users = $repo->find($users);
       $form = $this->createForm(FormUserType::class, $users);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) { 

        $em->flush();

        $this->addFlash('success', 'The user was be updated succefuly');

        return $this->redirectToRoute('app_user');
       }
        $formulaire = $form->createView();
        return $this->render('user/update.html.twig', compact('formulaire','users'));
    }
}
