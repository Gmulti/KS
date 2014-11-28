<?php

namespace KS\ServerBundle\Manager;

use KS\ServerBundle\Exception\ScopeNotFoundException;

class ClientManager
{
    private $mongo;

    /**
     * @var ScopeManagerInterface
     */
    private $sm;

    public function __construct($mongo, ScopeManagerInterface $scopeManager)
    {
        $this->mongo = $mongo;
        $this->sm = $scopeManager;
    }

    /**
     * Creates a new client
     *
     * @param string $identifier
     *
     * @param array $redirect_uris
     *
     * @param array $grant_type
     *
     * @param array $scopes
     *
     * @return Client
     */
    public function createClient($identifier, array $redirect_uris = array(), array $grant_types = array(), array $scopes = array())
    {
        $client = new \KS\ServerBundle\Document\Client();
        $client->setClientId($identifier);
        $client->setClientSecret($this->generateSecret());
        $client->setRedirectUri($redirect_uris);
        $client->setGrantTypes($grant_types);

        // Verify scopes
        foreach ($scopes as $scope) {
            // Get Scope
            $scopeObject = $this->sm->findScopeByScope($scope);
            if (!$scopeObject) {
                throw new ScopeNotFoundException();
            }
        }

        $client->setScopes($scopes);

        $dm = $this->mongo->getManager();
        // Store Client
        $dm->persist($client);
        $dm->flush();

        return $client;
    }

    /**
     * Creates a secret for a client
     *
     * @return A secret
     */
    protected function generateSecret()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
}
