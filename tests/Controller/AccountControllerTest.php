<?php

namespace test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider providerUrls
     */
    public function testAccountControllerRoutes($url)
    {
        $this->client->request('GET', $url);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function providerUrls()
    {
        return [
            ['/user/profile/'],
            ['/user/update/'],
            ['/user/update/password/new']
        ];
    }
}