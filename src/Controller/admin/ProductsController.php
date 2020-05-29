<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $productsRepo;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager, ProductRepository $productsRepo)
    {
        $this->manager = $manager;
        $this->productsRepo = $productsRepo;
    }

    /**
     * @Route("/admin/products/show/{page}", name="admin_products_show")
     * @return Response
     */
    public function index(PaginationService $pagination, $page = 1): Response
    {
        $pagination->setEntityClass(Product::class)
                    ->setCurrentPage($page)
                    ->setOrderBy(['quantity' => 'ASC']);

        return $this->render('admin/products/show.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/products/delete/{id}", name="admin_products_delete")
     * @return Response
     */
    public function delete(Product $product): Response
    {
        $this->manager->remove($product);
        $this->manager->flush();

        $this->addFlash('success', 'Votre produit a bien été supprimé');

        return $this->redirectToRoute('admin_products_show');
    }

    /**
     * @Route("/admin/products/add", name="admin_products_add")
     * @Route("/admin/products/update/{id}", name="admin_products_update")
     * @return Response
     */
    public function add_or_update(Product $product = null, Request $request)
    {
        $exist = true;
        $message = 'Votre produit a bien été modifié.';
        if(!$product) {
            $product = new Product;
            $message = 'Votre produit a bien été ajouté à la liste des produits.';
            $exist = false;
        };

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($product);
            $this->manager->flush();
            $this->addFlash('success', $message);
            return $this->redirectToRoute('admin_products_show');
        }

        return $this->render('admin/products/add.html.twig', [
            'form' => $form->createView(),
            'exist' => $exist,
            'product' => $product
        ]);
    }
}
