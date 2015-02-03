<?php

namespace KS\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use KS\MediaBundle\Document\Media as Media;
use KS\PostBundle\Document\Post as Post;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;


use Symfony\Component\Security\Core\User\UserInterface;
use KS\ServerBundle\User\OAuth2UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @MongoDB\Document
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @MongoDBUnique(fields="email")
 * @MongoDBUnique(fields="username")
 *
 * @Serializer\XmlRoot("user")
 *
 * @Hateoas\Relation("posts", href = "expr('/users/' ~ object.getUsername() ~ '/posts')")
 * @Hateoas\Relation("sharePosts", href = "expr('/users/' ~ object.getUsername() ~ '/sharePosts')")
 * @Hateoas\Relation("comments", href = "expr('/users/' ~ object.getUsername() ~ '/comments')")
 *
 * @ExclusionPolicy("all") 
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     * @Expose
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\Type(type="string", message="This {{ value }} is not a {{ type }} valid.")
     * @Expose
     */
    protected $lastname;

    /**
     * @MongoDB\String
     * @Assert\Type(type="string", message="This {{ value }} is not a {{ type }} valid.")
     * @Expose
     */
    protected $firstname;

    /**
     * @MongoDB\Date
     * @Expose
     */
    protected $birthday;

    /**
     * @MongoDB\ReferenceMany(
     *     targetDocument="KS\MediaBundle\Document\Media", 
     *     inversedBy="user", 
     *     cascade={"all"}
     * )
     * @Expose
     */
    protected $medias;

    /**
     * @MongoDB\ReferenceMany(
     *    targetDocument="KS\PostBundle\Document\Post", 
     *    inversedBy="user", 
     *    cascade={"all"}
     * )
     * @Expose
     */
    protected $posts;

    /**
     * @MongoDB\ReferenceMany(targetDocument="KS\PostBundle\Document\Post", inversedBy="usersShared")
     * @Expose
     */
    protected $sharePosts;

    /**
     * @MongoDB\ReferenceMany(targetDocument="KS\PostBundle\Document\Comment", inversedBy="user")
     * @Expose
     */
    protected $comments;

    /**
     * @MongoDB\ReferenceMany(targetDocument="KS\PostBundle\Document\Post", inversedBy="likes")
     * @Expose
     */
    protected $userLikes;

     /**
     * @MongoDB\Collection
     */
    protected $scopes;

    /**
     * @var date $created
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     * @Expose
     */
    private $created;

    /**
     * @var date $updated
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable
     * @Expose
     */
    private $updated;

    /**
     * @MongoDB\Date
     * @Expose
     */
    protected $deletedAt;

    public function __construct()
    {
        parent::__construct();
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
     * Set lastname
     *
     * @param string $lastname
     * @return self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
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
     * Add sharePost
     *
     * @param KS\PostBundle\Document\Post $sharePost
     */
    public function addSharePost(\KS\PostBundle\Document\Post $sharePost)
    {
        $this->sharePosts[] = $sharePost;
    }

    /**
     * Remove sharePost
     *
     * @param KS\PostBundle\Document\Post $sharePost
     */
    public function removeSharePost(\KS\PostBundle\Document\Post $sharePost)
    {
        $this->sharePosts->removeElement($sharePost);
    }

    /**
     * Get sharePosts
     *
     * @return Doctrine\Common\Collections\Collection $sharePosts
     */
    public function getSharePosts()
    {
        return $this->sharePosts;
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

    public function getName(){
        return $this->firstname;
    }



    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return implode(' ', $this->getScopes());
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
     * Set scopes
     *
     * @param collection $scopes
     * @return self
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * Get scopes
     *
     * @return collection $scopes
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Set birthday
     *
     * @param date $birthday
     * @return self
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Get birthday
     *
     * @return date $birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Add userLike
     *
     * @param KS\PostBundle\Document\Post $userLike
     */
    public function addUserLike(\KS\PostBundle\Document\Post $userLike)
    {
        $this->userLikes[] = $userLike;
    }

    /**
     * Remove userLike
     *
     * @param KS\PostBundle\Document\Post $userLike
     */
    public function removeUserLike(\KS\PostBundle\Document\Post $userLike)
    {
        $this->userLikes->removeElement($userLike);
    }

    /**
     * Get userLikes
     *
     * @return Doctrine\Common\Collections\Collection $userLikes
     */
    public function getUserLikes()
    {
        return $this->userLikes;
    }

    /**
     * Add media
     *
     * @param KS\MediaBundle\Document\Media $media
     */
    public function addMedia(\KS\MediaBundle\Document\Media $media)
    {
        $this->medias[] = $media;
    }

    /**
     * Remove media
     *
     * @param KS\MediaBundle\Document\Media $media
     */
    public function removeMedia(\KS\MediaBundle\Document\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return Doctrine\Common\Collections\Collection $medias
     */
    public function getMedias()
    {
        return $this->medias;
    }

    public function __toString(){
        return $this->getUsername();
    }
}
