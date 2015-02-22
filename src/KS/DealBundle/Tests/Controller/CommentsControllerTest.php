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



}
