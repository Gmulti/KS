<?php

namespace KS\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\Fixtures\Document\LoadPageData;

use KS\DealBundle\Tests\GeneralController;

class UsersCommentsControllerTest extends GeneralController
{

    protected static $idUser;

    private static function getUser(){
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/users/test.json',
            array(),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        
        if(isset($msg['id'])):
            return $msg['id'];
        endif;

        return null;
    }


  	 public function testGetComments(){
        fwrite(STDOUT, __METHOD__ . "\n");
        
        self::$idUser = (self::$idUser == null ) ? self::getUser() : self::$idUser ;

        if(self::$idUser !== null){
            $client = static::createClient();

            $crawler = $client->request('GET', 
                '/api/v1/users/' . self::$idUser . '/comments.json',
                array(),
                array(),
                self::$headers
            );

            $response = $client->getResponse();
            $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
            $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

            $msg = json_decode($response->getContent(), true);

   
        }
        else{
            fwrite(STDOUT, 'No test get comments' . "\n");
        } 
    }

    // public function testGetComment(){
    //     fwrite(STDOUT, __METHOD__ . "\n");
        
    //     self::$idUser = (self::$idUser == null ) ? self::getUser() : self::$idUser ;
        
    //     if(self::$idComment !== null && self::$idUser !== null){
    //         $client = static::createClient();

    //         $crawler = $client->request('GET', 
    //             '/api/v1/users/' . self::$idUser . '/comments/' . self::$idComment . '.json',
    //             array(
    //                 'content'  => 'Commentaire test'
    //             ),
    //             array(),
    //             self::$headers
    //         );

    //         $response = $client->getResponse();
    //         $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
    //         $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

    //         $msg = json_decode($response->getContent(), true);

    //         $this->assertEquals($msg['content'], 'Commentaire test');

    //     }
    //     else{
    //         fwrite(STDOUT, 'No test get comment' . "\n");
    //     } 
    // }

}
