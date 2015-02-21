<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\Fixtures\Document\LoadPageData;

class CategoriesDealsControllerTest extends WebTestCase
{

	protected static $username = 'test';

    protected static $password = 'test';


    public function testGetDealsFromCategoryOffsetLimitDefault()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/deals.json',
            array(),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        // Test if result default offset : 0 / limit 10
        $this->assertGreaterThanOrEqual(count($msg), 10);

        if(isset($msg[0])):
            self::$idDeal = $msg[0]['id'];
        endif;

    }

    /**
     * @depends testGetDealsOffsetLimitDefault
     */
    public function testGetDeal(){
        fwrite(STDOUT, __METHOD__ . "\n");
        if(!empty(self::$idDeal)):
            $client = static::createClient();
            $crawler = $client->request('GET', 
                '/api/v1/deals/' . self::$idDeal . '.json',
                array(),
                array(),
                self::$headers
            );

            $response = $client->getResponse();
            $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
            $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

            $msg = json_decode($response->getContent(), true);
        else:
            $this->assertTrue(false);
        endif;
    }

    public function testPostDealNoMedia(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('POST', 
            '/api/v1/deals.json',
            array(
                'user' => self::$username,
                'content'  => 'Contenu test'
            ),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        $msg = json_decode($response->getContent(), true);

        $this->assertEquals($msg['user']['username'], self::$username);
        $this->assertEquals($msg['content'], 'Contenu test');

        self::$idDeal = $msg['id'];
    }

    /**
     * @depends testPostDealNoMedia
     */
    public function testPutDealNoMedia(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('PUT', 
            '/api/v1/deals/' . self::$idDeal . '.json',
            array(
                'content'  => 'update test'
            ),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        $msg = json_decode($response->getContent(), true);

        $this->assertEquals($msg['user']['username'], self::$username);
        $this->assertEquals($msg['content'], 'update test');
    }

    /**
     * @depends testPostDealNoMedia
     */
    public function testDeleteDealNoMedia(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('DELETE', 
            '/api/v1/deals/' . self::$idDeal . '.json',
            array(),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(202, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        $msg = json_decode($response->getContent(), true);
        $this->assertEquals($msg['success'], 'delete_success');
    }

}
