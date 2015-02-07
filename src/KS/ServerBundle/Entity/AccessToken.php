<?php

namespace KS\ServerBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="access_token")
 */
class AccessToken extends AbstractToken
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $token;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expires;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $scope;

    /**
     * @ORM\ManyToOne(targetEntity="KS\ServerBundle\Entity\Client", inversedBy="client_id")
     */
    protected $client;

    public function __construct(array $roles = array())
    {
        parent::__construct($roles);
        // Si l'utilisateur a des rôles, on le considère comme authentifié
        $this->setAuthenticated(count($roles) > 0);
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
     * Set user_id
     *
     * @param  string      $userId
     * @return AccessToken
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
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
            // @see https://github.com/bshaffer/oauth2-server-bundle/issues/24
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
     * Set client
     *
     * @param  \KS\ServerBundle\Entity\Client $client
     * @return AccessToken
     */
    public function setClient(\KS\ServerBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \KS\ServerBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

        public function getCredentials()
    {
        return '';
    }
}