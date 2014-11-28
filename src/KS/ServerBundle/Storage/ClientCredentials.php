<?php

namespace KS\ServerBundle\Storage;

use OAuth2\Storage\ClientCredentialsInterface;
use KS\ServerBundle\Entity\Client;

class ClientCredentials implements ClientCredentialsInterface
{
    private $mongo;

    public function __construct($mongo)
    {
        $this->mongo = $mongo;
    }

   
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        // Get Client
        $client = $this->mongo->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);

        // If client exists check secret
        if ($client) {
            return $client->getClientSecret() === $client_secret;
        }

        return false;
    }

   
    public function getClientDetails($client_id)
    {

        // Get Client
        $client = $this->mongo->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);

        if (!$client) {
            return false;
        }

        return array(
            'redirect_uri' => implode(' ', $client->getRedirectUri()),
            'client_id' => $client->getClientId(),
            'grant_types' => $client->getGrantTypes()
        );
    }

   
    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        $client = $this->getClientDetails($client_id);

        if (!$client) {
            return false;
        }

        if (empty($client['grant_types'])) {
            return true;
        }

        if (in_array($grant_type, $client['grant_types'])) {
            return true;
        }

        return false;
    }

    
    public function isPublicClient($client_id)
    {
        $client = $this->mongo->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);

        if (!$client) {
            return false;
        }

        $secret = $client->getClientSecret();

        return empty($secret);
    }

  
    public function getClientScope($client_id)
    {
        // Get Client
        $client = $this->mongo->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);

        if (!$client) {
            return false;
        }

        return implode(' ', $client->getScopes());
    }
}
