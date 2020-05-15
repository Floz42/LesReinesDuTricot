<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }
    public function testHomePage()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testOneProductPage() 
    {
        $this->client->request('GET', '/product/show/23');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}