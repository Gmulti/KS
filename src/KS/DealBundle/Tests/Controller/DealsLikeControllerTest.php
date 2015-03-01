<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\GeneralController;

class DealsLikeControllerTest extends GeneralController
{
    protected static $idDeal = null;

    private static function getDeal(){
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/deals.json',
            array(),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        
        if(isset($msg[0]['id'])):
            return $msg[0]['id'];
        endif;

        return null;
    }

    public function testPostLikeDeal(){
        fwrite(STDOUT, __METHOD__ . "\n");

        self::$idDeal = (self::$idDeal == null ) ? self::getDeal() : self::$idDeal ;

        if (self::$idDeal !== null) {
		    $client = static::createClient();

		    $crawler = $client->request('POST', 
		        '/api/v1/deals/' . self::$idDeal . '/likes.json',
		        array(),
		        array(),
		        self::$headers
		    );

		    $response = $client->getResponse();
		    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
		    $msg = json_decode($response->getContent(), true);

		    if ($response->getStatusCode() == 404) {
		    	 $this->assertEquals($msg['error'], 'already_like');
		    }
		    else{
		    	$this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');
		    	$this->assertEquals($msg['id'], self::$idDeal);
		    }
		  

		   

		}
		else{
			fwrite(STDOUT, 'No test post like deal' . "\n");
		} 
    }

    public function testPostDislikeDeal(){
        fwrite(STDOUT, __METHOD__ . "\n");

        self::$idDeal = (self::$idDeal == null ) ? self::getDeal() : self::$idDeal ;

        if (self::$idDeal !== null) {
		    $client = static::createClient();

		    $crawler = $client->request('POST', 
		        '/api/v1/deals/' . self::$idDeal . '/dislikes.json',
		        array(),
		        array(),
		        self::$headers
		    );

		    $response = $client->getResponse();
		    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
		    $msg = json_decode($response->getContent(), true);

		    if ($response->getStatusCode() == 404) {
		    	 $this->assertEquals($msg['error'], 'no_like');
		    }
		    else{
		    	$this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');
		    	$this->assertEquals($msg['id'], self::$idDeal);
		    }		  
		}
		else{
			fwrite(STDOUT, 'No test post dislike deal' . "\n");
		} 
    }

}
