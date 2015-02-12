<?php

namespace KS\ServerBundle\Manager;

use Doctrine\ORM\EntityManager;
use OAuth2\ServerBundle\Exception\ScopeNotFoundException;
use KS\ServerBundle\Entity\Client;
use OAuth2\ServerBundle\Entity\Client as OAuthClient;
use OAuth2\ServerBundle\Manager\ScopeManagerInterface;

class ClientManager
{
    private $em;

    /**
     * @var ScopeManagerInterface
     */
    private $sm;

    public function __construct(EntityManager $entityManager, ScopeManagerInterface $scopeManager)
    {
        $this->em = $entityManager;
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
        $client = new Client();
        $client->setClientId($identifier);
        $secret = $this->generateSecret();
        $client->setClientSecret($secret);
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

        $clientOauth = new OAuthClient();
        $clientOauth->setClientId($identifier);
        $clientOauth->setClientSecret($secret);
        $clientOauth->setRedirectUri($redirect_uris);
        $clientOauth->setGrantTypes($grant_types);
        $clientOauth->setScopes($scopes);

        // Store Client
        $this->em->persist($clientOauth);
        $this->em->persist($client);
        $this->em->flush();

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
