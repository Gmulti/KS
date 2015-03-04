<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\GeneralController;

class UsersFollowControllerTest extends GeneralController
{
    protected static $idUser = null;

    private static function getUser(){
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/users/testadmin.json',
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

    public function testPostFollowUser(){
        fwrite(STDOUT, __METHOD__ . "\n");

        self::$idUser = (self::$idUser == null ) ? self::getUser() : self::$idUser ;

        if (self::$idUser !== null) {
		    $client = static::createClient();

		    $crawler = $client->request('POST', 
		        '/api/v1/users/' . self::$idUser . '/follows.json',
		        array(),
		        array(),
		        self::$headers
		    );

		    $response = $client->getResponse();
		    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
		    $msg = json_decode($response->getContent(), true);

		    if ($response->getStatusCode() == 404) {
		    	 $this->assertEquals($msg['error'], 'already_follow');
		    }
		    else{
		    	$this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');
		    	$this->assertEquals($msg['id'], self::$idUser);
		    }
		  

		   

		}
		else{
			fwrite(STDOUT, 'No test post follow user' . "\n");
		} 
    }

    public function testPostUnfollowUser(){
        fwrite(STDOUT, __METHOD__ . "\n");

        self::$idUser = (self::$idUser == null ) ? self::getUser() : self::$idUser ;

        if (self::$idUser !== null) {
		    $client = static::createClient();

		    $crawler = $client->request('POST', 
		        '/api/v1/users/' . self::$idUser . '/unfollows.json',
		        array(),
		        array(),
		        self::$headers
		    );

		    $response = $client->getResponse();
		    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
		    $msg = json_decode($response->getContent(), true);

		    if ($response->getStatusCode() == 404) {
		    	 $this->assertEquals($msg['error'], 'no_follow');
		    }
		    else{
		    	$this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');
		    	$this->assertEquals($msg['id'], self::$idUser);
		    }		  
		}
		else{
			fwrite(STDOUT, 'No test post unfollow user' . "\n");
		} 
    }

}
