<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\Fixtures\Document\LoadPageData;

use KS\DealBundle\Tests\GeneralController;

class CommentsControllerTest extends GeneralController
{

    private function getDeal(){
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/deals.json'
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        
        if(isset($msg[0])):
            self::$idDeal = $msg[0]['id'];
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



}
