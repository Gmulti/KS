<?php

namespace KS\MediaBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use KS\PageBundle\Document\Page as Page;
use KS\UserBundle\Document\User as User;
use KS\PostBundle\Document\Post as Post;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Image
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\ReferenceOne(targetDocument="KS\UserBundle\Document\User", mappedBy="images")
     */
    protected $user;

    /**
     * @MongoDB\ReferenceOne(targetDocument="KS\PostBundle\Document\Post", mappedBy="image")
     */
    protected $post;

    /**
     * @var date $created
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var date $updated
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable
     */
    private $updated;

    /**
     * @var date $updated
     *
     * @MongoDB\Date
     */
    private $deletedAt;

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
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return self
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Get nom
     *
     * @return string $nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set user
     *
     * @param KS\MediaBundle\Document\User $user
     * @return self
     */
    public function setUser(\KS\UserBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return KS\MediaBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set post
     *
     * @param KS\PostBundle\Document\Post $post
     * @return self
     */
    public function setPost(\KS\PostBundle\Document\Post $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * Get post
     *
     * @return KS\PostBundle\Document\Post $post
     */
    public function getPost()
    {
        return $this->post;
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

    /**
     * Set deletedAt
     *
     * @param date $deletedAt
     * @return self
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return date $deletedAt
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
}
