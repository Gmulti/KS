<?php

namespace KS\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KS\UserBundle\Tests\Fixtures\Document\LoadPageData;

class UsersControllerTest extends WebTestCase
{

    protected static $token;

    protected static $headers;

    protected static $idUser;

    protected static $em;

    protected static $newUser = 'newtest';

    protected static $newPassword = 'newtest';

    protected static $newEmail = 'newtest@email.com';

    protected static $clientId;

    protected static $clientSecret;

    public static function setUpBeforeClass()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        self::$em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        
        if ($_SERVER['PWD'] == "/var/www/api.komunitystore") {
            self::$clientId = '317b47172';
            self::$clientSecret = '598thil5yr4sgg00k08cww8gowcc8cg';
        }
        else{
            self::$clientId = '12345';
            self::$clientSecret = 'tcwg6gajmqoksgswwws0wgsscgwssgc';
        }

    }

    public static function tearDownAfterClass(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $accessToken = self::$em->getRepository('KSServerBundle:AccessToken')
                            ->findOneByToken(self::$token);
        if(null !== $accessToken):
            self::$em->remove($accessToken);
            self::$em->flush();
        endif;
    }


    public function testRegisterUser()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('POST', 
            '/api/public/users/register.json',
            array(
            	'username' => self::$newUser,
            	'password' => self::$newPassword,
            	'email'    => self::$newEmail
            ),
            array()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        if (isset($content['error'])) {
            $this->assertEquals(400, $response->getStatusCode(), $content['error']);
        }
        else{
            $this->assertEquals(200, $response->getStatusCode());
        }


        if(isset($content['id'])):
            self::$idUser = $content['id'];
        endif;

    }

    /**
     * @depends testRegisterUser
     */
    public function testOAuthUser(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $client = static::createClient();

        $crawler = $client->request('POST', 
            '/token',
            array(
                'grant_type' => 'password',
                'client_id' => self::$clientId,
                'client_secret' => self::$clientSecret,
                'username' => self::$newUser,
                'password' => self::$newPassword,
                'scope' => 'public'
            ),
            array()
        );

        $response = $client->getResponse();
        $result = json_decode($response->getContent(), true);

        self::$token = $result['access_token'];
        self::$headers = array('HTTP_AUTHORIZATION' => 'Bearer ' . self::$token);
    }

    /**
     * @depends testOAuthUser
     */
    public function testDeleteUser(){
        fwrite(STDOUT, __METHOD__ . "\n");
    	$client = static::createClient();

        $crawler = $client->request('DELETE', 
            '/api/v1/users/' . self::$idUser . '.json',
            array(),
            array(),
            self::$headers
        );

        $response = $client->getResponse();
        $msg = json_decode($response->getContent(), true);

        if(isset($msg['success'])){
            $this->assertEquals(202, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');
            $this->assertEquals($msg['success'], 'delete_success');
        }
        else{
            $this->assertEquals(404, $response->getStatusCode(), 'Erreur serveur, dumper [0]["message"]');

        }
       
    }

}