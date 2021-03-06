<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService 
{
    /**
     * @var SessionInterface $session
     */
    protected $session;

    /**
     * @var CategoryRepository
     */
    private $categoryRepo;

    /**
     * @var ProductRepository
     */ 
    private $productRepo;

    private $requestStack;
    
    /**
     * __construct
     *
     * @param  SessionInterface $session
     * @return void
     */
    public function __construct(SessionInterface $session, CategoryRepository $categoryRepo, ProductRepository $productRepo, RequestStack $requestStack)
    {
        $this->session = $session;
        $this->categoryRepo = $categoryRepo;
        $this->productRepo = $productRepo;
        $this->requestStack = $requestStack;
    }
    
    /**
     * setSession ->  
     *
     * @return void
     */
    public function setSession()
    {
        if (!$this->session->has('category')) {
            $category = [];
            foreach ($this->categoryRepo->findAll() as $cat) {
                $category[] = [$cat->getId() => $cat->getName()];
            } 
            return $this->session->set('category',$category);
        }
        return;
    }

    /**
     * Get $session
     *
     * @return  SessionInterface
     */ 
    public function getSession()
    {
        return $this->session;
    }
    
    /**
     * addProduct
     *
     * @param  int $id
     * @return void
     */
    public function addProduct(int $id)
    {
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
        $this->setTotalProducts();
    }
    
    /**
     * removeProduct
     *
     * @param  int $id
     * @return void
     */
    public function removeProduct(int $id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);

        $this->session->set('cart', $cart);
        $this->setTotalProducts();
    }
    
    /**
     * updateProduct -> update a product in cart section
     *
     * @param  mixed $id
     * @return void
     */
    public function updateProduct(int $id)
    {
        $cart = $this->session->get('cart', []);

        $newQuantity = $this->requestStack->getMasterRequest()->request->get("valeur");
        $cart[$id] = $newQuantity;
        if ($cart[$id] == 0) {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
        $this->setTotalProducts();    
    } 
    
    /**
     * ramoveAllProducts Remove all products presents in cart
     *
     * @return void
     */
    public function removeAllProducts()
    {
        $this->session->set('cart', []);
        $this->setTotalProducts();
    }
    
    /**
     * getFullCart
     *
     * @return array
     */
    public function getFullCart(): array
    {
        $cart = $this->session->get('cart', []);

        $cartWithData = [];

        foreach($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $this->productRepo->find($id),
                'quantity' => $quantity
            ];
        }
        
        return $cartWithData;
    }
    
    /**
     * getTotal
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;
        foreach($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }
    
    /**
     * getTotalProduct
     *
     * @return int
     */
    public function setTotalProducts(): void
    {
        $total = 0;
        
        foreach($this->getFullCart() as $item) {
            $total += $item['quantity'];
        }

         $this->session->set('totalproducts', $total);
    }
    
    /**
     * verifyProductQuantityIsAvailable
     *
     * @return array
     */
    public function verifyProductQuantityIsAvailable(): array
    {
        $cart = $this->session->get('cart', []);

        $notAvailable = [];
        foreach($this->getFullCart() as $key => $product) {
            if ($product["product"]) {
                if ($product["product"]->getQuantity() < $product['quantity']) {
                    $notAvailable[] = ["title" => $product["product"]->getTitle(), 'quantity' => $product["product"]->getQuantity()];
                } 
            } else {
                $this->session->set('cart', []);
                $this->setTotalProducts();
            }
        }
        return $notAvailable;
    }
}
