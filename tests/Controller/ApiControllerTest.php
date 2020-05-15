<?php 

namespace test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider providerUrls
     */
    public function testApiRoutes($url)
    {
        $this->client->request('GET', $url);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function providerUrls()
    {
        return [
            ['/api/v1/products/show'],
            ['/api/v1/category/show'],
            ['/api/v1/products/show/25'],
            ['/api/v1/category/show/3'],
            ['/api/v1/products/show/last/products']
        ];
    }
}