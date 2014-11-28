<?php

namespace KS\ServerBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document
 */
class Client
{
    /**
     * @MongoDB\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $clientId;

    /**
     * @MongoDB\String
     */
    protected $clientSecret;

    /**
     * @MongoDB\Collection
     */
    protected $redirectUri;

    /**
     * @MongoDB\Collection
     */
    protected $grantTypes;
    
    /**
     * @MongoDB\String
     */
    protected $publicKey;

    /**
     * @MongoDB\Collection
     */
    protected $scopes;

    /**
     * @MongoDB\Collection
     */
    protected $users;

   
    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set clientId
     *
     * @param string $clientId
     * @return self
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * Get clientId
     *
     * @return string $clientId
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set clientSecret
     *
     * @param string $clientSecret
     * @return self
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * Get clientSecret
     *
     * @return string $clientSecret
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set redirectUri
     *
     * @param collection $redirectUri
     * @return self
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    /**
     * Get redirectUri
     *
     * @return collection $redirectUri
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * Set grantTypes
     *
     * @param collection $grantTypes
     * @return self
     */
    public function setGrantTypes($grantTypes)
    {
        $this->grantTypes = $grantTypes;
        return $this;
    }

    /**
     * Get grantTypes
     *
     * @return collection $grantTypes
     */
    public function getGrantTypes()
    {
        return $this->grantTypes;
    }

    /**
     * Set publicKey
     *
     * @param KS\ServerBundle\Document\ClientPublicKey $publicKey
     * @return self
     */
    public function setPublicKey(\KS\ServerBundle\Document\ClientPublicKey $publicKey)
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * Get publicKey
     *
     * @return KS\ServerBundle\Document\ClientPublicKey $publicKey
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set scopes
     *
     * @param collection $scopes
     * @return self
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * Get scopes
     *
     * @return collection $scopes
     */
    public function getScopes()
    {
        return $this->scopes;
    }


    /**
     * Set users
     *
     * @param collection $users
     * @return self
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * Get users
     *
     * @return collection $users
     */
    public function getUsers()
    {
        return $this->users;
    }
}
