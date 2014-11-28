<?php

namespace KS\ServerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * @MongoDB\Document
 */
class AccessToken extends AbstractToken
{
    /**
     * @MongoDB\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $token;

    /**
     * @MongoDB\String
     */
    protected $userId;

    /**
     * @MongoDB\Date
     */
    protected $expires;

    /**
     * @MongoDB\String
     */
    protected $scope;

   /**
     * @MongoDB\String
     */
    protected $client;


    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // Si l'utilisateur a des rôles, on le considère comme authentifié
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }


    /**
     * Set token
     *
     * @param  string      $token
     * @return AccessToken
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
     * @param  string      $userId
     * @return AccessToken
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
     * @param  \DateTime   $expires
     * @return AccessToken
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
     * @param  string      $scope
     * @return AccessToken
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

    public function getUsername(){
        return $this->userId;
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
