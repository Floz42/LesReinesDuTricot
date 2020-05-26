<?php

namespace tests\Controller\admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testCategoryShowAll()
    {
        $this->client->request('GET', '/admin/category/show');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
/* 
    public function testCategoryDelete() 
    {
        $category = new Category;
        $category->setName("Test");

        $categoryRepo = $this->createMock(CategoryRepository::class);
        $categoryRepo->expects($this->any())
            ->method('find')
            ->willReturn($category);

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects($this->any())
            ->method('getRepository')
            ->willReturn($categoryRepo);
        dd($category);
        $this->client->request('GET', "/admin/category/delete/$category");
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    } */

    public function testCategoryAdd() 
    {
        $this->client->request('GET', "admin/category/add");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}