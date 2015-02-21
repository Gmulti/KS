<?php

namespace KS\DealBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\DealBundle\Tests\Fixtures\Document\LoadPageData;

class GeneralController extends WebTestCase
{

	protected static $username = 'test';

    protected static $password = 'test';

    protected static $em;
    
    protected static $token;

    protected static $headers;

    protected static $clientId;

    protected static $clientSecret;
    
    public static function setUpBeforeClass()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        self::$em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        
        self::$clientId = '317b47172';
        self::$clientSecret = 'jsz8bll8p6o0ocww8ssg4ccwcoowcw8';

        $client = static::createClient();

        $crawler = $client->request('POST', 
            '/token',
            array(
                'grant_type' => 'password',
                'client_id' => self::$clientId,
                'client_secret' => self::$clientSecret,
                'username' => self::$username,
                'password' => self::$password,
                'scope' => 'public'
            ),
            array()
        );

        $response = $client->getResponse();
        $result = json_decode($response->getContent(), true);

        self::$token = $result['access_token'];
        self::$headers = array('HTTP_AUTHORIZATION' => 'Bearer ' . self::$token);
    }

    public static function tearDownAfterClass(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $accessToken = self::$em->getRepository('KSServerBundle:AccessToken')
                            ->findOneByToken(self::$token);

        self::$em->remove($accessToken);
        self::$em->flush();
    }


}
