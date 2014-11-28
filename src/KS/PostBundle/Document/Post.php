<?php

namespace KS\PostBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use KS\MediaBundle\Document\Image as Image;
use KS\UserBundle\Document\User as User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Post
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $content;

    /**
     * @MongoDB\Float
     */
    protected $rating;

    /**
     * @MongoDB\ReferenceOne(targetDocument="KS\MediaBundle\Document\Image", mappedBy="post")
     */
    protected $image;

    /**
     * @MongoDB\ReferenceOne(targetDocument="KS\UserBundle\Document\User", mappedBy="posts")
     */
    protected $user;

   /**
     * @MongoDB\ReferenceMany(targetDocument="KS\UserBundle\Document\User", inversedBy="sharePosts")
     */
    protected $usersShared;

    /**
     * @MongoDB\ReferenceMany(targetDocument="KS\PostBundle\Document\Comment", inversedBy="post")
     */
    protected $comments;


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

    public function __construct()
    {
        $this->usersShared = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set content
     *
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set image
     *
     * @param KS\MediaBundle\Document\Image $image
     * @return self
     */
    public function setImage(\KS\MediaBundle\Document\Image $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return KS\MediaBundle\Document\Image $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set user
     *
     * @param KS\UserBundle\Document\User $user
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
     * @return KS\UserBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add usersShared
     *
     * @param KS\UserBundle\Document\User $usersShared
     */
    public function addUsersShared(\KS\UserBundle\Document\User $usersShared)
    {
        $this->usersShared[] = $usersShared;
    }

    /**
     * Remove usersShared
     *
     * @param KS\UserBundle\Document\User $usersShared
     */
    public function removeUsersShared(\KS\UserBundle\Document\User $usersShared)
    {
        $this->usersShared->removeElement($usersShared);
    }

    /**
     * Get usersShared
     *
     * @return Doctrine\Common\Collections\Collection $usersShared
     */
    public function getUsersShared()
    {
        return $this->usersShared;
    }

    /**
     * Set note
     *
     * @param float $note
     * @return self
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * Get note
     *
     * @return float $note
     */
    public function getNote()
    {
        return $this->note;
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

    /**
     * Add comment
     *
     * @param KS\PostBundle\Document\Comment $comment
     */
    public function addComment(\KS\PostBundle\Document\Comment $comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * Remove comment
     *
     * @param KS\PostBundle\Document\Comment $comment
     */
    public function removeComment(\KS\PostBundle\Document\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection $comments
     */
    public function getComments()
    {
        return $this->comments;
    }
}
