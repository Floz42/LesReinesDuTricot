<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Product;
use App\Form\ContactType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\FaqRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    

    public function __construct(CategoryRepository $categoryRepo, ProductRepository $productsRepo, FaqRepository $faqRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->productsRepo = $productsRepo;
        $this->faqRepo = $faqRepo;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(\Swift_Mailer $mailer, Request $request)
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
                        ->setSubject('Nouveau message de ton site !')
                        ->setFrom($data->getEmail())
                        ->setTo('flo.carreclub@gmail.com')
                        ->setContentType('text/html')
                        ->setBody(
                            '<html>' . 
                                '<body>' . 
                                    'Nouveau message de : '. $data->getFirstname() . ' ' . $data->getLastname() . '<br>' .
                                    'Son e-mail : '. $data->getEmail() . '<br>' .
                                    'Contenu du message : ' . $data->getMessage() . '<br>' .
                                '</body>' . 
                            '</html>' 
                        );
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
    */
    public function show_product($id, Product $product) 
    {
        return $this->render('home/products/show_product.html.twig', [
            'category' => $this->categoryRepo->findAll(),
            'product' => $this->productsRepo->find($id)
        ]);
    }
}
