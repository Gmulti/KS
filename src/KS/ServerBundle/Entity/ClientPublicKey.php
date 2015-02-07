<?php

namespace KS\ServerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client_public_key")
 */
class ClientPublicKey
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="KS\ServerBundle\Entity\ClientPublicKey")
     */
    private $client;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $client_id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $public_key;

    /**
     * Set client
     *
     * @param  \KS\ServerBundle\Entity\Client $client
     * @return ClientPublicKey
     */
    public function setClient(\KS\ServerBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        // this is necessary as the client_id is the primary key
        $this->client_id = $client->getClientId();

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

    /**
     * Set public key
     *
     * @param  string  $public_key
     * @return Client
     */
    public function setPublicKey($public_key)
    {
        $this->public_key = $public_key;

        return $this;
    }

    /**
     * Get public key
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }
}
