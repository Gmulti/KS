<?php

namespace KS\PostBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\PostBundle\Tests\Fixtures\Document\LoadPageData;

class PostsControllerTest extends WebTestCase
{

    protected $token;

    protected $headers;

    protected static $idDeal;

    protected $username = 'test';

    protected $password = 'test';

    public function setUp()
    {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        
        if ($_SERVER['PWD'] == "/var/www/api.komunitystore") {
            $clientId = '317b47172';
            $clientSecret = '598thil5yr4sgg00k08cww8gowcc8cg';
        }
        else{
            $clientId = '12345';
            $clientSecret = 'tcwg6gajmqoksgswwws0wgsscgwssgc';
        }

        $client = static::createClient();

        $crawler = $client->request('POST', 
            '/token',
            array(
                'grant_type' => 'password',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'username' => $this->username,
                'password' => $this->password,
                'scope' => 'public'
            ),
            array()
        );

        $response = $client->getResponse();
        $result = json_decode($response->getContent(), true);

        $this->token = $result['access_token'];
        $this->headers = array('HTTP_AUTHORIZATION' => 'Bearer ' . $this->token);
    }

    public function tearDown(){
        $accessToken = $this->em->getRepository('KSServerBundle:AccessToken')
                            ->findOneByToken($this->token);

        $this->em->remove($accessToken);
        $this->em->flush();
    }


    public function testGetDealsOffsetLimitDefault()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 
            '/api/v1/deals.json',
            array(),
            array(),
            $this->headers
        );

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Cela doit être une erreur serveur');

        // Test if result default offset : 0 / limit 10
        $msg = json_decode($response->getContent(), true);
        $this->assertGreaterThanOrEqual(count($msg), 10);

        if(isset($msg[0])):
            self::$idDeal = $msg[0]['id'];
        endif;

    }

    /**
     * @depends testGetDealsOffsetLimitDefault
     */
    public function testGetDeal(){
    
        if(!empty(self::$idDeal)):
            $client = static::createClient();
            $crawler = $client->request('GET', 
                '/api/v1/deals/' . self::$idDeal . '.json',
                array(),
                array(),
                $this->headers
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
        $client = static::createClient();

        $crawler = $client->request('POST', 
            '/api/v1/deals.json',
            array(
                'user' => $this->username,
                'content'  => 'Contenu test'
            ),
            array(),
            $this->headers
        );

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(200, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        $msg = json_decode($response->getContent(), true);

        $this->assertEquals($msg['user']['username'], $this->username);
        $this->assertEquals($msg['content'], 'Contenu test');
    }





}
