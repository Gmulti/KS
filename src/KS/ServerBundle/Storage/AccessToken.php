<?php

namespace KS\ServerBundle\Storage;

use OAuth2\Storage\AccessTokenInterface;
use Doctrine\ORM\EntityManager;
use KS\ServerBundle\Entity\Client;
use KS\ServerBundle\Entity\AccessToken as AT;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class AccessToken extends AbstractToken implements AccessTokenInterface
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Look up the supplied oauth_token from storage.
     *
     * We need to retrieve access token data as we create and verify tokens.
     *
     * @param $oauth_token
     * oauth_token to be check with.
     *
     * @return
     * An associative array as below, and return NULL if the supplied oauth_token
     * is invalid:
     * - client_id: Stored client identifier.
     * - expires: Stored expiration in unix timestamp.
     * - scope: (optional) Stored scope values in space-separated string.
     *
     * @ingroup oauth2_section_7
     */
    public function getAccessToken($oauth_token)
    {

        $accessToken = $this->em->getRepository('KSServerBundle:AccessToken')->findOneByToken($oauth_token);
        // var_dump($accessToken);
        // die();
        if (!$accessToken) {
            return null;
        }

        // Get Client
        $client = $accessToken->getClient();

        return array(
            'client_id' => $client->getClientId(),
            'access_token' => $accessToken->getToken(),
            'token_type' => "Bearer",
            'user_id' => $accessToken->getUserId(),
            'expires_in' => $accessToken->getExpires()->getTimestamp(),
            'expires' => $accessToken->getExpires()->getTimestamp(),
            'scope' => $accessToken->getScope()
        );
    }

    /**
     * Store the supplied access token values to storage.
     *
     * We need to store access token data as we create and verify tokens.
     *
     * @param $oauth_token
     * oauth_token to be stored.
     * @param $client_id
     * Client identifier to be stored.
     * @param $user_id
     * User identifier to be stored.
     * @param int    $expires
     *                        Expiration to be stored as a Unix timestamp.
     * @param string $scope
     *                        (optional) Scopes to be stored in space-separated string.
     *
     * @ingroup oauth2_section_4
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null)
    {
        // Get Client Entity
        $client = $this->em->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);

        if (!$client) {
            return null;
        }

        // Create Access Token
        $accessToken = new AT();
        $accessToken->setToken($oauth_token);
        $accessToken->setClient($client);
        $accessToken->setUserId($user_id);
        $accessToken->setExpires($expires);
        $accessToken->setScope($scope);

        // Store Access Token
        $this->em->persist($accessToken);
        $this->em->flush();
    }

    public function getCredentials(){
        return '';
    }
}
