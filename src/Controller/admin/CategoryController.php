<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\StatsService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var CategoryRepository
     */
    private $categoryRepo;

    public function __construct(EntityManagerInterface $manager, CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/category/show", name="admin_category_show")
     * @return Response
     */
    public function show(ProductRepository $productsRepo): Response
    {
        return $this->render('admin/category/show.html.twig', [
            'category' => $this->categoryRepo->findAll(),
            'products' => $productsRepo->findAll()
        ]);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="admin_category_delete")
     * @return Response
     */
    public function delete(Category $category): Response
    {
        $this->manager->remove($category);
        $this->manager->flush();
        $this->addFlash('success', "Votre catégorie a bien été supprimée.");

        return $this->redirectToRoute('admin_category_show');
    }

    /**
     * @Route("/admin/category/add", name="admin_category_add")
     * @Route("/admin/category/update/{id}", name="admin_category_update")
     * @return Response
     */
    public function add_update(Request $request, Category $category = null): Response
    {
        $exist = true;
        $message = "Votre catégorie a été modifiée avec succès";
        if (!$category) {
            $category = new Category();
            $exist = false;
            $message = "Vote catégorie a bien été ajoutée";
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($category);
            $this->manager->flush();
            $this->addFlash('success', $message);
            return $this->redirectToRoute("admin_category_show");
        }

        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
            'exist' => $exist
        ]);
    }


}