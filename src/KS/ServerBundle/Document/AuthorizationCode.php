<?php

namespace KS\ServerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @MongoDB\Document
 */
class AuthorizationCode
{

    /**
     * @MongoDB\Id(strategy="AUTO")
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $code;

    /**
     * @MongoDB\Date
     */
    private $expires;

    /**
     * @MongoDB\String
     */
    private $userId;

    /**
     * @MongoDB\Collection
     */
    private $redirectUri;

    /**
     * @MongoDB\String
     */
    private $scope;

    /**
     * @MongoDB\String
     */
    private $client;

    /**
     * Set code
     *
     * @param  string            $code
     * @return AuthorizationCode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set expires
     *
     * @param  \DateTime         $expires
     * @return AuthorizationCode
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
     * Set userId
     *
     * @param  string            $userId
     * @return AuthorizationCode
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
     * Set redirectUri
     *
     * @param  string            $redirectUri
     * @return AuthorizationCode
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = explode(' ', $redirectUri);

        return $this;
    }

    /**
     * Get redirectUri
     *
     * @return array
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * Set scope
     *
     * @param  string            $scope
     * @return AuthorizationCode
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
