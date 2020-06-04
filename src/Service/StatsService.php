<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatsService 
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var CommandRepository
     */
    private $commandRepo;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepo, ProductRepository $productRepo, CommandRepository $commandRepo) 
    {
        $this->manager = $manager;
        $this->productRepo = $productRepo;
        $this->userRepo = $userRepo;
        $this->commandRepo = $commandRepo;
    }
    
    /**
     * getUsersCount -> count number of users in database
     *
     * @return void
     */
    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    /**
     * getTypeProductsCount -> count number of unique products in database
     *
     * @return void
     */
    public function getTypeProductsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(p) FROM App\Entity\Product p')->getSingleScalarResult(); 
    }

    /**
     * getCommandsCount -> count number of users in database
     *
     * @return void
     */
    public function getCommandsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Command c')->getSingleScalarResult();
    }
    
    /**
     * getLowProductsCount -> get products who are less 5 quantity
     *
     * @return void
     */
    public function getLowProductsCount()
    {
        return $this->manager->createQuery('SELECT p FROM App\Entity\Product p WHERE p.quantity <= 3 ORDER BY p.quantity')->getArrayResult();
    }
    
    /**
     * getTotalQuantityProducts -> get total off products quantity in database
     *
     * @return void
     */
    public function getTotalQuantityProducts()
    {
        return $this->manager->createQuery('SELECT SUM(p.quantity) FROM App\Entity\Product p')->getSingleScalarResult();
    }
    
    /**
     * bestProductsSold -> get three best products sold
     *
     * @return void
     */
    public function bestProductsSold()
    {
        return $this->manager->createQuery('SELECT p FROM App\Entity\Product p ORDER BY p.sold DESC')->setMaxResults(3)->getArrayResult();
    }

    /**
     * worstProductsSold -> get three worst products sold
     *
     * @return void
     */
    public function worstProductsSold()
    {
        return $this->manager->createQuery(
            'SELECT p 
            FROM App\Entity\Product p
            WHERE p.visible = 1 
            ORDER BY p.sold')
            ->setMaxResults(3)
            ->getArrayResult();
    }
        
    /**
     * getAverageCart -> get average cart
     *
     * @return void
     */
    public function getAverageCard()
    {
        return $this->manager->createQuery('SELECT AVG(c.total) FROM App\Entity\Command c')->getSingleScalarResult();
    }
    
    /**
     * getTotalProductsSold -> get total of products sold
     *
     * @return void
     */
    public function getTotalProductsSold()
    {
        return $this->manager->createQuery('SELECT SUM(p.sold) FROM App\Entity\Product p')->getSingleScalarResult();
    }
    
    /**
     * getRevenue -> get the total revenue
     *
     * @return void
     */
    public function getRevenue()
    {
        return $this->manager->createQuery('SELECT SUM(c.total) FROM App\Entity\Command c')->getSingleScalarResult();
    }
    
    /**
     * getBestBuyers -> get three users who as more commands
     *
     * @return void
     */
    public function getBestBuyers()
    {
        return $this->manager->createQuery(
            'SELECT COUNT(c.user) as commands, a.username as user
            FROM App\Entity\Command c 
            JOIN c.user a
            GROUP BY c.user 
            ORDER BY commands DESC'
        )
        ->setMaxResults(5)
        ->getArrayResult()
        ;
    }

    /**
     * getStats -> get all stats in a compact variable
     *
     * @return void
     */
    public function getStats()
    {
        $users = $this->getUsersCount();
        $products = $this->getTypeProductsCount();
        $commands = $this->getCommandsCount();
        $quantityProducts = $this->getTotalQuantityProducts();
        $lowProductsCount = $this->getLowProductsCount();
        $bestProducts = $this->bestProductsSold();
        $worstProducts = $this->worstProductsSold();
        $averageCard = $this->getAverageCard();
        $bestBuyers = $this->getBestBuyers();
        $totalProductsSold = $this->getTotalProductsSold();
        $revenue = $this->getRevenue();

        return compact('users', 
                       'products', 
                       'commands', 
                       'lowProductsCount', 
                       'quantityProducts', 
                       'bestProducts', 
                       'worstProducts', 
                       'averageCard',
                       'bestBuyers',
                       'totalProductsSold',
                       'revenue'
                    );
    }
}