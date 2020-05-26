<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\SessionService;
use App\Repository\FaqRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepo;

    /**
     * @var ProductRepository
     */
    private $productsRepo;

    /**
     * @var FaqRepository
     */
    private $faqRepo;

    private $session;
    
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(CategoryRepository $categoryRepo, ProductRepository $productsRepo, FaqRepository $faqRepo, EntityManagerInterface $manager, SessionService $session)
    {
        $this->categoryRepo = $categoryRepo;
        $this->productsRepo = $productsRepo;
        $this->faqRepo = $faqRepo;
        $this->manager = $manager;
        $this->session = $session;
        $session->setSession();

    }

    /**
     * @Route("/", name="home")
     * 
     * @param \Swift_Mailer $mailer
     * @param Request $request
     * 
     * @return Response
     */
    public function index(\Swift_Mailer $mailer, Request $request): Response
    {
        $contact = new Contact(); 

        $form_contact = $this->createForm(ContactType::class, $contact);
        $form_contact->handleRequest($request);

        if ($form_contact->isSubmitted()) {
            if (!$form_contact->isValid()) {
                $this->addFlash('error', "Votre message n'a pas pu être envoyé, votre formulaire comporte une/des erreur(s).");
            } else {
                $data = $form_contact->getData();
                $message = (new \Swift_Message())
                        ->setSubject('Nouveau message de votre site !')
                        ->setFrom($data->getEmail())
                        ->setTo('flo.carreclub@gmail.com')
                        ->setContentType('text/html')
                        ->setBody($this->renderView('partials/mails/contact.html.twig', ['data' => $data ]));
                $mailer->send($message);
                $this->addFlash('success', "Votre message a été envoyé avec succès.");
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('home/index.html.twig', [
            'category' => $this->categoryRepo->findAll(),
            'products' => $this->productsRepo->findAll(),
            'lastProducts' => $this->productsRepo->lastProducts(),
            'form_contact' => $form_contact->createView(),
            'faq' => $this->faqRepo->findAll() 
        ]);
    }

    /** 
    * @Route("/product/show/{id}", name="show_product")
    *
    * @param Int $id
    *
    * @return Response
    */
    public function show_product(Int $id): Response 
    {
        return $this->render('home/products/show_product.html.twig', [
            'product' => $this->productsRepo->find($id)
        ]);
    }

}
