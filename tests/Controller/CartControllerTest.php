<?php

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase {

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testCartControllerCartRoute()
    {
        $this->client->request('GET', '/cart');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCartControllerCartAddRoute()
    {
        $this->client->request('GET', '/cart/add/25');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

}