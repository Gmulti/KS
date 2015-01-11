<?php

namespace KS\ServerBundle\Storage;

use OAuth2\Storage\RefreshTokenInterface;

class RefreshToken implements RefreshTokenInterface
{
    private $mongo;

    public function __construct($mongo)
    {
        $this->mongo = $mongo;
    }

    
    public function getRefreshToken($refresh_token)
    {
        $refreshToken = $this->mongo->getRepository('KSServerBundle:RefreshToken')->findOneByToken($refresh_token);

        if (!$refreshToken) {
            return null;
        }

        return array(
            'refresh_token' => $refreshToken->getToken(),
            'client_id' => $refreshToken->getClient(),
            'user_id' => $refreshToken->getUserId(),
            'expires' => $refreshToken->getExpires()->getTimestamp(),
            'scope' => $refreshToken->getScope()
        );
    }

    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        // Get Client Document
        $client = $this->mongo->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);
        if (!$client) {
            return null;
        }

        // Create Refresh Token
        $refreshToken = new \KS\ServerBundle\Document\RefreshToken();
        $refreshToken->setToken($refresh_token);
        $refreshToken->setClient($client_id);
        $refreshToken->setUserId($user_id);
        $refreshToken->setExpires($expires);
        $refreshToken->setScope($scope);

        $dm = $this->mongo->getManager();
        // Store Refresh Token
        $dm->persist($refreshToken);
        $dm->flush();
    }

    public function unsetRefreshToken($refresh_token)
    {
        $refreshToken = $this->mongo->getRepository('KSServerBundle:RefreshToken')->find($refresh_token);
        
        $dm = $this->mongo->getManager();

        $dm->remove($refreshToken);
        $dm->flush();
    }
}
