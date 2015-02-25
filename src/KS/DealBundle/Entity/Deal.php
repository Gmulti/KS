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
use KS\DealBundle\Models\LikeEntityInterface;



/**
 * @ORM\Table(name="ks_deal")
 * @ORM\Entity(repositoryClass="KS\DealBundle\Entity\DealRepository")
 * 
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @Hateoas\Relation("user", href = "expr('users/' ~ object.getUser() )")
 * 
 * @ExclusionPolicy("all") 
 */
class Deal implements LikeEntityInterface
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
     * @Assert\NotBlank()
     * @Expose()
     */
    protected $content;


    /**
     * @ORM\OneToMany(targetEntity="KS\MediaBundle\Entity\Media", mappedBy="deal",  cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Expose()
     */
    protected $medias;

    /**
     * @ORM\OneToMany(targetEntity="KS\DealBundle\Entity\Comment", mappedBy="deal",  cascade={"all"})
     * @ORM\JoinColumn(nullable=true)
     * @Expose()
     */
    protected $comments;

    /**
     * @ORM\ManyToOne(targetEntity="KS\UserBundle\Entity\User", inversedBy="deals", cascade={"persist"})
     * @Assert\NotBlank()
     * @Assert\Type(type="KS\UserBundle\Entity\User")
     * @Expose()
     */
    protected $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $url;

    /**
     * @ORM\ManyToMany(targetEntity="KS\UserBundle\Entity\User", cascade={"persist"}, inversedBy="dealsShared")
     * @ORM\JoinTable(name="users_shared")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $usersShared;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose()
     */
    protected $nbUsersShared;

    /**
     * @ORM\ManyToMany(targetEntity="KS\DealBundle\Entity\Category", cascade={"persist"}, inversedBy="deals")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $categories;

    /**
     * @ORM\ManyToMany(targetEntity="KS\DealBundle\Entity\Type", cascade={"persist"}, inversedBy="deals")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $types;

    /**
     * @ORM\ManyToMany(targetEntity="KS\UserBundle\Entity\User", cascade={"persist"}, inversedBy="dealsLikes")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $usersLikes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose()
     */
    protected $nbUsersLikes;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Expose()
     */
    protected $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Expose()
     */
    protected $lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Expose()
     */
    protected $lng;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $address;

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
        $this->usersShared = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->types = new ArrayCollection();
    }
   

    public function __toString(){
        return $this->content;
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
     * @return Deal
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
     * Set url
     *
     * @param string $url
     * @return Deal
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Deal
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Deal
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Deal
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Deal
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Deal
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
     * @return Deal
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
     * @return Deal
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
     * Add medias
     *
     * @param \KS\MediaBundle\Entity\Media $medias
     * @return Deal
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
     * Add comments
     *
     * @param \KS\DealBundle\Entity\Comment $comments
     * @return Deal
     */
    public function addComment(\KS\DealBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \KS\DealBundle\Entity\Comment $comments
     */
    public function removeComment(\KS\DealBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set user
     *
     * @param \KS\UserBundle\Entity\User $user
     * @return Deal
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
     * Add usersShared
     *
     * @param \KS\UserBundle\Entity\User $usersShared
     * @return Deal
     */
    public function addUsersShared(\KS\UserBundle\Entity\User $usersShared)
    {
        $this->usersShared[] = $usersShared;

        return $this;
    }

    /**
     * Remove usersShared
     *
     * @param \KS\UserBundle\Entity\User $usersShared
     */
    public function removeUsersShared(\KS\UserBundle\Entity\User $usersShared)
    {
        $this->usersShared->removeElement($usersShared);
    }

    /**
     * Get usersShared
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsersShared()
    {
        return $this->usersShared;
    }

    /**
     * Add categories
     *
     * @param \KS\DealBundle\Entity\Category $categories
     * @return Deal
     */
    public function addCategory(\KS\DealBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \KS\DealBundle\Entity\Category $categories
     */
    public function removeCategory(\KS\DealBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add types
     *
     * @param \KS\DealBundle\Entity\Type $types
     * @return Deal
     */
    public function addType(\KS\DealBundle\Entity\Type $types)
    {
        $this->types[] = $types;

        return $this;
    }

    /**
     * Remove types
     *
     * @param \KS\DealBundle\Entity\Type $types
     */
    public function removeType(\KS\DealBundle\Entity\Type $types)
    {
        $this->types->removeElement($types);
    }

    /**
     * Get types
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Add usersLikes
     *
     * @param \KS\UserBundle\Entity\User $usersLikes
     * @return Deal
     */
    public function addUsersLike(\KS\UserBundle\Entity\User $usersLikes)
    {
        $this->usersLikes[] = $usersLikes;

        return $this;
    }

    /**
     * Remove usersLikes
     *
     * @param \KS\UserBundle\Entity\User $usersLikes
     */
    public function removeUsersLike(\KS\UserBundle\Entity\User $usersLikes)
    {
        $this->usersLikes->removeElement($usersLikes);
    }

    /**
     * Get usersLikes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsersLikes()
    {
        return $this->usersLikes;
    }

    /**
     * Set nbUsersLikes
     *
     * @param integer $nbUsersLikes
     * @return Deal
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
     * Set nbUsersShared
     *
     * @param integer $nbUsersShared
     * @return Deal
     */
    public function setNbUsersShared($nbUsersShared)
    {
        $this->nbUsersShared = $nbUsersShared;

        return $this;
    }

    /**
     * Get nbUsersShared
     *
     * @return integer 
     */
    public function getNbUsersShared()
    {
        return $this->nbUsersShared;
    }
}
