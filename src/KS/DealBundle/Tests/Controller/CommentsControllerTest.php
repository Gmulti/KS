<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\Fixtures\Document\LoadPageData;

use KS\DealBundle\Tests\GeneralController;

class CommentsControllerTest extends GeneralController
{

    protected static $idDeal;

    protected static $idComment;

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

    public function testPostCommentNoMedia(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $idDeal = self::getDeal();

        if ($idDeal !== null) {
            $client = static::createClient();

            $crawler = $client->request('POST', 
                '/api/v1/deals/' . $idDeal . '/comments.json',
                array(
                    'content'  => 'Commentaire test'
                ),
                array(),
                self::$headers
            );

            $response = $client->getResponse();
            $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
            $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

            $msg = json_decode($response->getContent(), true);

            $this->assertEquals($msg['content'], 'Commentaire test');

            if(isset($msg['id'])):
                self::$idDeal = $idDeal;
                self::$idComment = $msg['id'];
            endif;

        }
        else{
            fwrite(STDOUT, 'No test post comment no media' . "\n");
        } 
    }

    /**
     * @depends testPostCommentNoMedia
     */
    public function testGetComment(){
        fwrite(STDOUT, __METHOD__ . "\n");

        if(self::$idComment !== null){
            $client = static::createClient();

            $crawler = $client->request('GET', 
                '/api/v1/deals/' . self::$idDeal . '/comments/' . self::$idComment . '.json',
                array(
                    'content'  => 'Commentaire test'
                ),
                array(),
                self::$headers
            );

            $response = $client->getResponse();
            $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
            $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

            $msg = json_decode($response->getContent(), true);

            $this->assertEquals($msg['content'], 'Commentaire test');

        }
        else{
            fwrite(STDOUT, 'No test get comment' . "\n");
        } 
    }

    public function testPostCommentNoConnect(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $idDeal = self::getDeal();

        if ($idDeal !== null) {
            $client = static::createClient();

            $crawler = $client->request('POST', 
                '/api/v1/deals/' . $idDeal . '/comments.json',
                array(
                    'content'  => 'Commentaire test'
                )
            );

            $response = $client->getResponse();
            $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
            $this->assertEquals(500, $response->getStatusCode());

        }
        else{
            fwrite(STDOUT, 'No test post comment no connect' . "\n");
        } 
    }

}
