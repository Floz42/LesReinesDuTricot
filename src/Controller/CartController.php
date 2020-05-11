<?php

namespace App\Controller;

use App\Service\SessionService;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @var ProductRepository $product
     */
    private $productRepo;

    /**
     * @var EntityManagerInterface
     */
    private $manager;
    
    /**
     * __construct
     *
     * @param  ProductRepository $productRepo
     * @return void
     */
    public function __construct(ProductRepository $productRepo, EntityManagerInterface $manager, SessionService $session)
    {
        $this->productRepo = $productRepo;
        $this->manager = $manager;
        $session->setSession();
    }

    /**
     * @Route("/cart", name="cart_index")
     * 
     * @param SessionService $sessionService
     * 
     * @return Response
     */
    public function index(SessionService $sessionService): Response
    {
        $cartWithData = $sessionService->getFullCart();
        $total = $sessionService->getTotal();


        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * 
     * @param SessionService $sessionService
     * @param int $id
     * 
     * @return Response
     */
    public function add(int $id, SessionService $sessionService): Response 
    {
        $sessionService->addProduct($id);

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     * 
     * @param SessionService $sessionService
     * @param int $id
     * 
     * @return Response
     */
    public function remove(SessionService $sessionService, int $id): Response
    {
        $sessionService->removeProduct($id);

        return $this->redirectToRoute('cart_index');
    }
}
