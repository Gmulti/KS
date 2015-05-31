<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\GeneralController;

class DealsControllerTest extends GeneralController
{
    protected static $idDeal;

    public function testGetDealsOffsetLimitDefaultNoConnect()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/deals.json'
        );

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(500, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

    }

    public function testGetDealsOffsetLimitDefault()
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

    public function testGetDealsWithStardAndEndPrice()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/deals.json',
            array(
                'start_price' => 9,
                'end_price'   => 11
            ),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        // Test if result default offset : 0 / limit 10
        $this->assertGreaterThanOrEqual(count($msg), 10);
        $this->assertGreaterThanOrEqual(9, $msg[0]['price']);

    }

    public function testGetDealsWithStardAndFalseEndPrice()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/deals.json',
            array(
                'start_price' => 9,
                'end_price'   => 7
            ),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        // Test if result default offset : 0 / limit 10
        $this->assertGreaterThanOrEqual(count($msg), 10);
        $this->assertGreaterThanOrEqual(9, $msg[0]['price']);

    }

    public function testGetDealsWithStardAndEndPriceAndGeolocalisation()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/deals.json',
            array(
                'start_price' => 5,
                'end_price'   => 20,
                'lng' => '4.854994',
                'lat' => '45.75537'
            ),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        $this->assertGreaterThanOrEqual(count($msg), 1);
        $this->assertGreaterThanOrEqual(15, $msg[0]['price']);

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
                'title' => 'test title',
                'content'  => 'Contenu test',
                'type' => 'bon-plan',
                'price' => '10',
                'currency' => 'dollar'
            ),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

       

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
        
        $client = self::deleteDeal(self::$idDeal);

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(202, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        $msg = json_decode($response->getContent(), true);

        $this->assertEquals($msg['success'], 'delete_success');
    }

    private static function deleteDeal($idDeal){
        $client = static::createClient();

        $crawler = $client->request('DELETE', 
            '/api/v1/deals/' . $idDeal . '.json',
            array(),
            array(),
            self::$headers
        );

        return $client;

    }


    public function testPostDealCategoryType(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('POST', 
            '/api/v1/deals.json',
            array(
                'user' => self::$username,
                'content'  => 'Contenu test',
                'categories' => array('categorie-1'),
                'type' => 'code-promo',
                'promoCode' => 'MYPROMOCODE',
                'currency' => 'euro'
            ),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        $msg = json_decode($response->getContent(), true);

        if (isset($msg['id'])) {
            self::deleteDeal($msg['id']);
        }
    }

}
