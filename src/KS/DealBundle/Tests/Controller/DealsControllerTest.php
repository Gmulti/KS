<?php

namespace KS\PostBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\PostBundle\Tests\Fixtures\Document\LoadPageData;

class PostsControllerTest extends WebTestCase
{

    protected $token;

    protected $headers;

    public function setUp()
    {
        $this->token = "46f5e87a85ddfae6a5054b050172bd1c414b47ff";
        $this->headers = array('Authorization' => 'Bearer ' . $this->token);
        $this->client = static::createClient();
    }

    public function testGetDeal(){
        
    }

    public function testGetDeals()
    {
        $crawler = $this->client->request('GET', 
            '/api/v1/deals.json',
            array(),
            array(),
            $this->headers
        );

        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Cela doit être une erreur serveur');

        // Test if result default offset : 0 / limit 10
        $msg = json_decode($response->getContent(), true);
        $this->assertGreaterThanOrEqual(count($msg), 10);

    }

    public function testPostPost(){
        
    }

}
