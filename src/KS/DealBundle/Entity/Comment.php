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



/**
 * @ORM\Entity
 * @ORM\Table(name="ks_comments")
 * 
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * 
 * @ExclusionPolicy("all") 
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=160)
     * @Expose()
     */
    protected $content;


    /**
     * @ORM\OneToOne(targetEntity="KS\MediaBundle\Entity\Media", mappedBy="comment")
     * @ORM\JoinColumn(nullable=true)
     * @Expose()
     */
    protected $medias;

    /**
     * @ORM\ManyToOne(targetEntity="KS\UserBundle\Entity\User", inversedBy="comments", cascade={"persist"})
     * @Assert\NotBlank()
     * @Assert\Type(type="KS\UserBundle\Entity\User")
     * @Expose()
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="KS\UserBundle\Entity\User", cascade={"persist"}, inversedBy="usersLikes")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $likes;


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

    public function __construct()
    {
        $this->likes = new ArrayCollection();
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
     * Set medias
     *
     * @param \KS\MediaBundle\Entity\Media $medias
     * @return Comment
     */
    public function setMedias(\KS\MediaBundle\Entity\Media $medias = null)
    {
        $this->medias = $medias;

        return $this;
    }

    /**
     * Get medias
     *
     * @return \KS\MediaBundle\Entity\Media 
     */
    public function getMedias()
    {
        return $this->medias;
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
     * Add likes
     *
     * @param \KS\UserBundle\Entity\User $likes
     * @return Comment
     */
    public function addLike(\KS\UserBundle\Entity\User $likes)
    {
        $this->likes[] = $likes;

        return $this;
    }

    /**
     * Remove likes
     *
     * @param \KS\UserBundle\Entity\User $likes
     */
    public function removeLike(\KS\UserBundle\Entity\User $likes)
    {
        $this->likes->removeElement($likes);
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLikes()
    {
        return $this->likes;
    }
}
