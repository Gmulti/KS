<?php

namespace KS\PostBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use KS\MediaBundle\Document\Image as Image;
use KS\UserBundle\Document\User as User;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @MongoDB\Document
 *
 * 
 * @Serializer\XmlRoot("geolocation")
 *
 */
class Geolocation
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     */
    protected $address;

    /**
     * @MongoDB\Float
     * @Assert\NotBlank()
     */
    protected $lat;

    /**
     * @MongoDB\ReferenceMany(targetDocument="KS\PostBundle\Document\Post", inversedBy="geolocation")
     */
    protected $posts;


    /**
     * @var date $created
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @var date $updated
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable
     */
    protected $updated;

  
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set address
     *
     * @param string $address
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return self
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * Get lat
     *
     * @return float $lat
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Add post
     *
     * @param KS\PostBundle\Document\Post $post
     */
    public function addPost(\KS\PostBundle\Document\Post $post)
    {
        $this->posts[] = $post;
    }

    /**
     * Remove post
     *
     * @param KS\PostBundle\Document\Post $post
     */
    public function removePost(\KS\PostBundle\Document\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection $posts
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set created
     *
     * @param date $created
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return date $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param date $updated
     * @return self
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return date $updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
