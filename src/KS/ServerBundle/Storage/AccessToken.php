<?php

namespace KS\ServerBundle\Storage;

use OAuth2\Storage\AccessTokenInterface;
use KS\ServerBundle\Document\Client;

class AccessToken implements AccessTokenInterface
{
    private $mongo;

    public function __construct($mongo)
    {
        $this->mongo = $mongo;
    }

    public function getAccessToken($oauth_token)
    {
        $accessToken = $this->mongo->getRepository('KSServerBundle:AccessToken')->findOneByToken($oauth_token);

        if (!$accessToken) {
            return null;
        }

        return array(
            'client_id' => $accessToken->getClient(),
            'user_id' => $accessToken->getUserId(),
            'expires' => $accessToken->getExpires()->getTimestamp(),
            'scope' => $accessToken->getScope()
        );
    }

    
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null)
    {

        // Get Client Document
        $client = $this->mongo->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);
        $user = $this->mongo->getRepository('KSUserBundle:User')->findOneByUsername($user_id);

        if (!$client && !$user) {
            return null;
        }

        // Create Access Token
        $accessToken = new \KS\ServerBundle\Document\AccessToken();
        $accessToken->setToken($oauth_token);
        $accessToken->setClient($client_id);
        $accessToken->setUserId($user_id);
        $accessToken->setExpires($expires);
        $accessToken->setScope($scope);
        $accessToken->setUser($user);


        $dm = $this->mongo->getManager();
        // Store Access Token
        $dm->persist($accessToken);
        $dm->flush();
    }
}
