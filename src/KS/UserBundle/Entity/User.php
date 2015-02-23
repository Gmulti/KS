<?php

namespace KS\UserBundle\Entity;

use KS\MediaBundle\Entity\Media as Media;
use KS\DealBundle\Entity\Deal as Deal;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use OAuth2\ServerBundle\User\OAuth2UserInterface;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ORM\Entity
 * @ORM\Table(name="ks_user")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @Serializer\XmlRoot("user")
 *
 * @Hateoas\Relation("deals", href = "expr('/users/' ~ object.getUsername() ~ '/deals')")
 * @Hateoas\Relation("shareDeals", href = "expr('/users/' ~ object.getUsername() ~ '/shareDeals')")
 * @Hateoas\Relation("comments", href = "expr('/users/' ~ object.getUsername() ~ '/comments')")
 *
 * @ExclusionPolicy("all") 
 */
class User extends BaseUser implements OAuth2UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Type(type="string", message="This {{ value }} is not a {{ type }} valid.")
     * @Expose
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Type(type="string", message="This {{ value }} is not a {{ type }} valid.")
     * @Expose
     */
    protected $firstname;


    /**
     * @ORM\OneToMany(targetEntity="KS\MediaBundle\Entity\Media", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     * @Expose()
     */
    protected $medias;

    /**
     * @ORM\ManyToMany(targetEntity="KS\DealBundle\Entity\Deal", cascade={"all"}, mappedBy="usersShared")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $dealsShared;

    /**
     * @ORM\OneToMany(targetEntity="KS\DealBundle\Entity\Deal", mappedBy="user", cascade={"all"})
     * @ORM\JoinColumn(nullable=true)
     * @Expose()
     */
    protected $deals;

    /**
     * @ORM\OneToMany(targetEntity="KS\DealBundle\Entity\Comment", mappedBy="user", cascade={"all"})
     * @ORM\JoinColumn(nullable=true)
     * @Expose()
     */
    protected $comments;

    /**
     * @ORM\Column(type="array")
     * @Expose()
     */
    protected $scopes;

    /**
     * @ORM\ManyToMany(targetEntity="KS\DealBundle\Entity\Deal", cascade={"all"}, mappedBy="usersLikes")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $dealsLikes;

    /**
     * @ORM\ManyToMany(targetEntity="KS\DealBundle\Entity\Comment", cascade={"all"}, mappedBy="usersLikesComment")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $commentsLikes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose()
     */
    protected $birthday;

    /**
     * @var date $created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Expose
     */
    private $created;

    /**
     * @var date $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable
     * @Expose
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     */
    protected $deletedAt;

    public function __construct()
    {
        parent::__construct();
        $this->medias = new ArrayCollection();
        $this->deals = new ArrayCollection();
        $this->dealsShared = new ArrayCollection();
        $this->enabled = true;
    }

    /**
     * Set scopes
     *
     * @param  array $scopes
     * @return User
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
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return 'public';  
        // return implode(' ', $this->getScopes());
    }

    /**
     * Erase credentials
     *
     * @return void
     */
    public function eraseCredentials()
    {
        // We don't hold anything sensitivie, do nothing
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
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * Add dealsShared
     *
     * @param \KS\DealBundle\Entity\Deal $dealsShared
     * @return User
     */
    public function addDealsShared(\KS\DealBundle\Entity\Deal $dealsShared)
    {
        $this->dealsShared[] = $dealsShared;

        return $this;
    }

    /**
     * Remove dealsShared
     *
     * @param \KS\DealBundle\Entity\Deal $dealsShared
     */
    public function removeDealsShared(\KS\DealBundle\Entity\Deal $dealsShared)
    {
        $this->dealsShared->removeElement($dealsShared);
    }

    /**
     * Get dealsShared
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDealsShared()
    {
        return $this->dealsShared;
    }

    /**
     * Add deals
     *
     * @param \KS\DealBundle\Entity\Deal $deals
     * @return User
     */
    public function addDeal(\KS\DealBundle\Entity\Deal $deals)
    {
        $this->deals[] = $deals;

        return $this;
    }

    /**
     * Remove deals
     *
     * @param \KS\DealBundle\Entity\Deal $deals
     */
    public function removeDeal(\KS\DealBundle\Entity\Deal $deals)
    {
        $this->deals->removeElement($deals);
    }

    /**
     * Get deals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDeals()
    {
        return $this->deals;
    }

    /**
     * Add comments
     *
     * @param \KS\DealBundle\Entity\Comment $comments
     * @return User
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
     * Add dealsLikes
     *
     * @param \KS\DealBundle\Entity\Deal $dealsLikes
     * @return User
     */
    public function addDealsLike(\KS\DealBundle\Entity\Deal $dealsLikes)
    {
        $this->dealsLikes[] = $dealsLikes;

        return $this;
    }

    /**
     * Remove dealsLikes
     *
     * @param \KS\DealBundle\Entity\Deal $dealsLikes
     */
    public function removeDealsLike(\KS\DealBundle\Entity\Deal $dealsLikes)
    {
        $this->dealsLikes->removeElement($dealsLikes);
    }

    /**
     * Get dealsLikes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDealsLikes()
    {
        return $this->dealsLikes;
    }

    /**
     * Add commentsLikes
     *
     * @param \KS\DealBundle\Entity\Comment $commentsLikes
     * @return User
     */
    public function addCommentsLike(\KS\DealBundle\Entity\Comment $commentsLikes)
    {
        $this->commentsLikes[] = $commentsLikes;

        return $this;
    }

    /**
     * Remove commentsLikes
     *
     * @param \KS\DealBundle\Entity\Comment $commentsLikes
     */
    public function removeCommentsLike(\KS\DealBundle\Entity\Comment $commentsLikes)
    {
        $this->commentsLikes->removeElement($commentsLikes);
    }

    /**
     * Get commentsLikes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentsLikes()
    {
        return $this->commentsLikes;
    }
}
