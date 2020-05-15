<?php

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp() 
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider providerUrlReturn
     */
    public function testUserControllerReturn($url) 
    {
        $this->client->request('GET', $url);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function providerUrlReturn()
    {
        return [
            ['/user/subscribe'],
            ['/login'],
            ['/password_lost'],
            ['/user/username_lost']
        ];
    }

     /**
      * @dataProvider providerUrlRedirect
      */
      public function testUserControllerRedirect($url)
      {
          $this->client->request('GET', $url);

          $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        }

      public function providerUrlRedirect()
      {
        return [
            ['/user/confirm'],
            ['/logout'],
            ['/user/updatePassword/confirm']
        ];
      }
}