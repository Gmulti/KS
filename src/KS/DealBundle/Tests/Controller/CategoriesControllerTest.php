<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\Fixtures\Document\LoadPageData;

use KS\DealBundle\Tests\GeneralController;

class CategoriesControllerTest extends GeneralController
{

	public function testGetCategoriesNoConnect(){
    	fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/categories.json'
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        $this->assertEquals(500, $response->getStatusCode());

    }

    public function testGetCategoryNoConnect(){
    	fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/categories/categorie-1.json'
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        $this->assertEquals(500, $response->getStatusCode());

    }

	public function testGetCategories(){
    	fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/categories.json',
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

    public function testGetCategory(){
    	fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/categories/categorie-1.json',
            array(),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');
        $this->assertEquals($msg['slug'], 'categorie-1');

    }


}
