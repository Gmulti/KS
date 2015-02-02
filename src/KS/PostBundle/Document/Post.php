<?php

namespace KS\PostBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use KS\MediaBundle\Document\Media as Media;
use KS\UserBundle\Document\User as User;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @MongoDB\Document
 *
 * 
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @Hateoas\Relation("user", href = "expr('users/' ~ object.getUser() )")
 * @Hateoas\Relation("comments", 
 *     href = "expr('posts/' ~ object.getId() ~ '/comments')",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getComments() !== null)")
 * )
 * @Hateoas\Relation("media", 
 *     href = "expr('medias/' ~ object.getMedia().getId() ~ '/url' )",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getMedia() === null)")
 * )
 * @ExclusionPolicy("all") 
 */
class Post
{
    /**
     * @MongoDB\Id
     * @Expose()
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Expose()
     */
    protected $content;


    /**
     * @MongoDB\ReferenceOne(
     *      targetDocument="KS\MediaBundle\Document\Media", 
     *      inversedBy="post", 
     *      cascade={"all"}
     * )
     * @Expose()
     */
    protected $media;

    /**
     * @MongoDB\ReferenceOne(
     *      targetDocument="KS\UserBundle\Document\User", 
     *      mappedBy="posts", 
     *      cascade={"all"}
     * )
     * @Assert\NotBlank()
     * @Assert\Type(type="KS\UserBundle\Document\User")
     * @Expose()
     */
    protected $user;

    /**
     * @MongoDB\String
     * @Expose()
     */
    protected $url;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="KS\UserBundle\Document\User", 
     *      inversedBy="sharePosts"
     * )
     */
    protected $usersShared;

    /**
     * @MongoDB\ReferenceOne(
     *      targetDocument="KS\PostBundle\Document\Geolocation",
     *      mappedBy="posts"
     * )
     * @Assert\Type(type="KS\PostBundle\Document\Geolocation")
     * @Expose()
     */
    protected $geolocation;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="KS\UserBundle\Document\User",
     *      inversedBy="userLikes"
     * )
     */
    protected $likes;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="KS\PostBundle\Document\Comment",
     *      inversedBy="post"
     * )
     * @Gedmo\ReferenceIntegrity("nullify")
     */
    protected $comments;

    /**
     * @MongoDB\Float
     *
     * @Expose()
     */
    protected $price;

    /**
     * @var date $created
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     * @Expose()
     */
    private $created;

    /**
     * @var date $updated
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable
     * @Expose()
     */
    private $updated;

    /**
     * @var date $deletedAt
     *
     * @MongoDB\Date
     */
    private $deletedAt;

    public function __construct()
    {
        $this->usersShared = new \Doctrine\Common\Collections\ArrayCollection();
        $this->likes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function removeUser()
    {
        $this->user = null;
        return $this;
    }


    /**
     * Set url
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
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


    /**
     * Add like
     *
     * @param KS\UserBundle\Document\User $like
     */
    public function addLike(\KS\UserBundle\Document\User $like)
    {
        $this->likes[] = $like;
    }

    /**
     * Remove like
     *
     * @param KS\UserBundle\Document\User $like
     */
    public function removeLike(\KS\UserBundle\Document\User $like)
    {
        $this->likes->removeElement($like);
    }

    /**
     * Get likes
     *
     * @return Doctrine\Common\Collections\Collection $likes
     */
    public function getLikes()
    {
        return $this->likes;
    }


    /**
     * Set geolocation
     *
     * @param KS\PostBundle\Document\Geolocation $geolocation
     * @return self
     */
    public function setGeolocation(\KS\PostBundle\Document\Geolocation $geolocation)
    {
        $this->geolocation = $geolocation;
        return $this;
    }

    /**
     * Get geolocation
     *
     * @return KS\PostBundle\Document\Geolocation $geolocation
     */
    public function getGeolocation()
    {
        return $this->geolocation;
    }

    /**
     * Set media
     *
     * @param KS\MediaBundle\Document\Media $media
     * @return self
     */
    public function setMedia(\KS\MediaBundle\Document\Media $media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get media
     *
     * @return KS\MediaBundle\Document\Media $media
     */
    public function getMedia()
    {
        return $this->media;
    }

    public function removeMedia(){
        $this->media = null;
        return $this;
    }

    /**
     * Set updated
     *
     * @param date $updated
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return date $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function __toString(){
        return $this->content;
    }
}
