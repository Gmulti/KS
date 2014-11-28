<?php

namespace KS\ServerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document
 */
class RefreshToken
{
    /**
     * @MongoDB\Id(strategy="AUTO")
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $token;

    /**
     * @MongoDB\String
     */
    private $userId;

    /**
     * @MongoDB\Date
     */
    private $expires;

    /**
     * @MongoDB\String
     */
    private $scope;

    /**
     * @MongoDB\String
     */
    private $client;

    /**
     * Set token
     *
     * @param  string       $token
     * @return RefreshToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set userId
     *
     * @param  string       $userId
     * @return RefreshToken
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set expires
     *
     * @param  \DateTime    $expires
     * @return RefreshToken
     */
    public function setExpires($expires)
    {
        if (!$expires instanceof \DateTime) {

            $dateTime = new \DateTime();
            $dateTime->setTimestamp($expires);
            $expires = $dateTime;
        }

        $this->expires = $expires;

        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set scope
     *
     * @param  string       $scope
     * @return RefreshToken
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
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
