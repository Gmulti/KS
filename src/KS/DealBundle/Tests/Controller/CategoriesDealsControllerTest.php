<?php

namespace KS\DealBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\Fixtures\Document\LoadPageData;

use KS\DealBundle\Tests\GeneralController;

class CategoriesDealsControllerTest extends GeneralController
{

	public function testGetDealsFromCategoryOffsetLimitDefaultNoConnect(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/categories/categorie-1/deals.json'
        );

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(500, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

    }

    public function testGetDealsFromCategoryOffsetLimitDefault()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/categories/categorie-1/deals.json',
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

    }

}
