<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\FormErrorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $productsRepo;

    /**
     * @var CategoryRepository
     */
    private $categoryRepo;

    private $formErrorService;


    public function __construct(ProductRepository $productsRepo, CategoryRepository $categoryRepo, FormErrorService $formErrorService)
    {
        $this->productsRepo = $productsRepo;
        $this->categoryRepo = $categoryRepo;
        $this->formErrorService = $formErrorService;
    }

    /**
     * Return all products presents in database
     * @Route("/api/v1/products/show", name="api_products_show", methods={"GET"})
     * @return Response
     */
    public function api_products(): Response
    {   
        $products = $this->productsRepo->findAll();
        return $this->json($products, 201, [], ['groups' => ['products:show']] );
    }

    /**
     * Return one product present in the database
     * @Route("/api/v1/products/show/{id}", name="api_products_show", methods={"GET"})
     * 
     * @var Product $product
     * 
     * @return Response
     */
    public function api_one_products(Product $product): Response
    {   
        return $this->json($product, 201, [], ['groups' => ['products:show']] );
    }

    /**
     * @Route("/api/v1/products/show/last", name="api_products_show_last", methods={"GET"})
     * 
     * @return Response
     */
    public function api_last_products(): Response
    {
        $lastProducts = $this->productsRepo->lastProducts();
        return $this->json($lastProducts, 201, [], ['groups' => ['products:show']]);
    }

    /**
     * Return all the category presents in the database and category associated
     * @Route("/api/v1/category/show", name="api_category_show", methods={"GET"})
     * 
     * @return Response
     */
    public function api_category(): Response
    {   
        $category = $this->categoryRepo->findAll();
        return $this->json($category, 201, [], ['groups' => ['category:show']] );
    }

    /**
     * Return one category present in the database
     * @Route("/api/v1/category/show/{id}", name="api_one_category_show", methods={"GET"})
     * 
     * @var Category $category
     * 
     * @return Response
     */
    public function api_one_category(Category $category): Response
    {   
        return $this->json($category, 201, [], ['groups' => ['category:show']] );
    }

    /**
     * @Route("/api/v1/category/post", name="post_product", methods={"POST"})
     * 
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * 
     * @return Response
     */
    public function post_category(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator): Response 
    {
        try {
            $category = $serializer->deserialize($request->getContent(), Category::class, 'json');

            $errors = $validator->validate($category);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $manager->persist($category);
            $manager->flush();

            return $this->json($category, 201, [
                "message" => "Catégorie bien ajoutée",
            ],
                ['groups' => ['category:create']] 
            );
        } catch(NotEncodableValueException $e) {
            return $this->json([
                "Status" => 400,
                "Message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/api/v1/category/post2", name="post_product_post2", methods={"POST"})
     */
    public function post_category3(Request $request, EntityManagerInterface $manager) 
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $data = json_decode($request->getContent(), true);
        if ($data === null){
            return $this->json(['message' => 'json invalid'], 400);
        }

        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();

            return $this->json($category, 200, [
                "message" => "Catégorie bien ajoutée"
            ],
                ['groups' => ['category:create']] 
            );
        }

        return $this->json($this->formErrorService->serializeErrors($form), 400);

    }
    }
