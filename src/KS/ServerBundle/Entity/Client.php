<?php

namespace KS\ServerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client
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
    protected $clientId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $clientSecret;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $redirectUri;

    /**
	 * @ORM\Column(type="array", nullable=true)
	 */
    protected $grantTypes;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $public_key;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $scopes;

    /**
     * Set clientId
     *
     * @param  string $clientId
     * @return Client
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set clientSecret
     *
     * @param  string $clientSecret
     * @return Client
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get clientSecret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set redirectUri
     *
     * @param  array  $redirectUri
     * @return Client
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;

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
     * Set grantTypes
     *
     * @param  array  $grantTypes
     * @return Client
     */
    public function setGrantTypes($grantTypes)
    {
        $this->grantTypes = $grantTypes;

        return $this;
    }

    /**
     * Get grantTypes
     *
     * @return array
     */
    public function getGrantTypes()
    {
        return $this->grantTypes;
    }


    /**
     * Set scopes
     *
     * @param  array  $scopes
     * @return Client
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * Get scopes
     *
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Set public key
     *
     * @param  \KS\ServerBundle\Entity\ClientPublicKey $public_key
     * @return Client
     */
    public function setPublicKey(\KS\ServerBundle\Entity\ClientPublicKey $public_key = null)
    {
        $this->public_key = $public_key;

        return $this;
    }

    /**
     * Get public key
     *
     * @return \KS\ServerBundle\Entity\ClientPublicKey
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }
}
