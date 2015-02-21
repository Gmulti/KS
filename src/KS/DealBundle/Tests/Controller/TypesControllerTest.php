<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\Fixtures\Document\LoadPageData;

use KS\DealBundle\Tests\GeneralController;

class TypesControllerTest extends GeneralController
{

	public function testGetTypesNoConnect(){
    	fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/types.json'
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        $this->assertEquals(500, $response->getStatusCode());

    }

    public function testGetTypeNoConnect(){
    	fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/types/bon-plan.json'
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        $this->assertEquals(500, $response->getStatusCode());

    }

	public function testGetTypes(){
    	fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/types.json',
            array(),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        // Test if result default offset : 0 / limit 10
        $this->assertGreaterThanOrEqual(count($msg), 10);
    }

    public function testGetType(){
    	fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/types/bon-plan.json',
            array(),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');
        $this->assertEquals($msg['slug'], 'bon-plan');

    }


}
