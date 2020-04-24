<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $manager;
    private $categoryRepo;
    private $productsRepo;

    public function __construct(EntityManagerInterface $manager, CategoryRepository $categoryRepo, ProductRepository $productsRepo)
    {
        $this->manager = $manager;
        $this->categoryRepo = $categoryRepo;
        $this->productsRepo = $productsRepo;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'category' => $this->categoryRepo->findAll(),
            'products' => $this->productsRepo->findAll(),
            'lastProducts' => $this->productsRepo->lastProducts()
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
