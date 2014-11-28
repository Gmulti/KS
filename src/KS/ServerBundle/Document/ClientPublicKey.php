<?php

namespace KS\ServerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document
 */
class ClientPublicKey
{
    /**
     * @MongoDB\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $client;

    /**
     * @MongoDB\Int
     */
    protected $clientId;

    /**
     * @MongoDB\String
     */
    protected $publicKey;

   

    /**
     * Set public key
     *
     * @param  string  $publicKey
     * @return Client
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get public key
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

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
     * @param int $clientId
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
     * @return int $clientId
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set client
     *
     * @param string $client
     * @return self
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return string $client
     */
    public function getClient()
    {
        return $this->client;
    }
}
