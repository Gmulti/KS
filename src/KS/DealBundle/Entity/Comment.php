<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KS\MediaBundle\Entity\Media as Media;
use KS\UserBundle\Entity\User as User;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Doctrine\Common\Collections\ArrayCollection;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

use KS\DealBundle\Models\ManyEntityInterface;


/**
 * @ORM\Entity(repositoryClass="KS\DealBundle\Entity\CommentRepository")
 * @ORM\Table(name="ks_comment")
 * 
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * 
 * @ExclusionPolicy("all") 
 */
class Comment implements ManyEntityInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="KS\DealBundle\Entity\Deal", inversedBy="comments", cascade={"persist"}) 
     * @Assert\NotBlank()
     * @Assert\Type(type="KS\DealBundle\Entity\Deal")
     */
    protected $deal;

    /**
     * @ORM\ManyToOne(targetEntity="KS\UserBundle\Entity\User", inversedBy="comments", cascade={"persist"})
     * @Assert\NotBlank()
     * @Assert\Type(type="KS\UserBundle\Entity\User")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=160)
     * @Assert\NotBlank()
     * @Expose()
     */
    protected $content;


    /**
     * @ORM\OneToMany(targetEntity="KS\MediaBundle\Entity\Media", mappedBy="comment",  cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Expose()
     */
    protected $medias;

    /**
     * @ORM\ManyToMany(targetEntity="KS\UserBundle\Entity\User", cascade={"persist"}, inversedBy="commentsLikes")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $usersLikesComment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose()
     */
    protected $nbUsersLikes;


    /**
     * @var date $created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Expose()
     */
    private $created;

    /**
     * @var date $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable
     * @Expose()
     */
    private $updated;

    /**
     * @var date $deletedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;
 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usersLikesComment = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Comment
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Comment
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set deal
     *
     * @param \KS\DealBundle\Entity\Deal $deal
     * @return Comment
     */
    public function setDeal(\KS\DealBundle\Entity\Deal $deal = null)
    {
        $this->deal = $deal;

        return $this;
    }

    /**
     * Get deal
     *
     * @return \KS\DealBundle\Entity\Deal 
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * Set user
     *
     * @param \KS\UserBundle\Entity\User $user
     * @return Comment
     */
    public function setUser(\KS\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \KS\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add medias
     *
     * @param \KS\MediaBundle\Entity\Media $medias
     * @return Comment
     */
    public function addMedia(\KS\MediaBundle\Entity\Media $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \KS\MediaBundle\Entity\Media $medias
     */
    public function removeMedia(\KS\MediaBundle\Entity\Media $medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Add usersLikesComment
     *
     * @param \KS\UserBundle\Entity\User $usersLikesComment
     * @return Comment
     */
    public function addUsersLikesComment(\KS\UserBundle\Entity\User $usersLikesComment)
    {
        $this->usersLikesComment[] = $usersLikesComment;
        $this->setNbUsersLikes($this->getNbUsersLikes()+1);

        return $this;
    }

    /**
     * Remove usersLikesComment
     *
     * @param \KS\UserBundle\Entity\User $usersLikesComment
     */
    public function removeUsersLikesComment(\KS\UserBundle\Entity\User $usersLikesComment)
    {
        $this->usersLikesComment->removeElement($usersLikesComment);
        $this->setNbUsersLikes($this->getNbUsersLikes()-1);
        return $this;
    }

    /**
     * Get usersLikesComment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsersLikesComment()
    {
        return $this->usersLikesComment;
    }

    /**
     * Set nbUsersLikes
     *
     * @param integer $nbUsersLikes
     * @return Comment
     */
    public function setNbUsersLikes($nbUsersLikes)
    {
        $this->nbUsersLikes = $nbUsersLikes;

        return $this;
    }

    /**
     * Get nbUsersLikes
     *
     * @return integer 
     */
    public function getNbUsersLikes()
    {
        return $this->nbUsersLikes;
    }

    /**
     * @VirtualProperty
     * @SerializedName("user")
     *
     */
    public function getUserSerialize()
    {   
        return array(
            'id' => $this->getUser()->getId(),
            'username' => $this->getUser()->getUsername()
        );
    }

}
