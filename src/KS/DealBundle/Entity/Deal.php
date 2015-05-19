<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KS\MediaBundle\Entity\Media as Media;
use KS\UserBundle\Entity\User as User;
use KS\DealBundle\Entity\Type as Type;
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
 * @ORM\Table(name="ks_deal")
 * @ORM\Entity(repositoryClass="KS\DealBundle\Entity\DealRepository")
 * 
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @Hateoas\Relation("user", href = "expr('users/' ~ object.getUser() )")
 * @Hateoas\Relation("comments", href = "expr('deals/' ~ object.getId() ~ '/comments')")
 * 
 * @ExclusionPolicy("all") 
 */
class Deal implements ManyEntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     * @Expose()
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=160)
     * @Assert\NotBlank()
     * @Expose()
     */
    protected $content;


    /**
     * @ORM\OneToMany(targetEntity="KS\MediaBundle\Entity\Media", mappedBy="deal",  cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"updated" = "DESC"})
     * @Expose()
     */
    protected $medias;

    /**
     * @ORM\OneToMany(targetEntity="KS\DealBundle\Entity\Comment", mappedBy="deal",  cascade={"all"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"updated" = "DESC"})
     */
    protected $comments;

    /**
     * @ORM\ManyToOne(targetEntity="KS\UserBundle\Entity\User", inversedBy="deals", cascade={"persist"})
     * @Assert\NotBlank()
     * @Assert\Type(type="KS\UserBundle\Entity\User")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $url;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $promoCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose()
     */
    protected $reduction;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices = {"pourcent", "cash"})
     * @Expose()
     */
    protected $reductionType;

    /**
     * @ORM\ManyToMany(targetEntity="KS\UserBundle\Entity\User", cascade={"persist"}, inversedBy="dealsShared")
     * @ORM\JoinTable(name="users_shared")
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"updated" = "DESC"})
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
     * @ORM\ManyToOne(targetEntity="KS\DealBundle\Entity\Type", inversedBy="deals", cascade={"persist"}) 
     * @Assert\NotBlank()
     * @Assert\Type(type="KS\DealBundle\Entity\Type")
     */
    protected $type;

    /**
     * @ORM\ManyToMany(targetEntity="KS\UserBundle\Entity\User", cascade={"persist"}, inversedBy="dealsLikes")
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"updated" = "DESC"})
     */
    protected $usersLikes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose()
     */
    protected $nbUsersLikes;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Expose()
     */
    protected $price;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices = {"euro", "dollar"})
     * @Assert\NotBlank()
     * @Expose()
     */
    protected $currency;

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
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose()
     */
    protected $expiration;

    /**
     * @var date $created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Expose()
     */
    protected $created;

    /**
     * @var date $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable
     * @Expose()
     */
    protected $updated;

    /**
     * @var date $deletedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;


    ////////////

    protected $alreadyLike;
    
    protected $alreadyShare;

    public function __construct()
    {
        $this->usersShared = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->nbUsersLikes = 0;
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
        $this->setNbUsersShared($this->getNbUsersShared()+1);
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
         $this->setNbUsersShared($this->getNbUsersShared()-1);
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
     * Add usersLikes
     *
     * @param \KS\UserBundle\Entity\User $usersLikes
     * @return Deal
     */
    public function addUsersLike(\KS\UserBundle\Entity\User $usersLikes)
    {
        $this->setNbUsersLikes($this->getNbUsersLikes()+1);
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
        $this->setNbUsersLikes($this->getNbUsersLikes()-1);
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


    /**
     * @VirtualProperty
     * @SerializedName("nb_comments")
     *
     * @return string
     */
    public function getCommentsSerialize()
    {   
        return count($this->getComments());
    }


    /**
     * @VirtualProperty
     * @SerializedName("user")
     *
     */
    public function getUserSerialize()
    {   
        return array(
            'id' => $this->getUser()->getID(),
            'username' => $this->getUser()->getUsername()

        );
    }

     /**
     * @VirtualProperty
     * @SerializedName("categories")
     */
    public function getCategoriesSerialize()
    {   
        $categories = $this->getCategories();
        $array = array();
        foreach ($categories as $key => $category) {
            array_push($array, $category->getTitle());
        }

        return $array;
    }

    /**
     * @VirtualProperty
     * @SerializedName("type")
     *
     */
    public function getTypesSerialize()
    {   
        return $this->getType()->getSlug();
    }


    /**
     * @VirtualProperty
     * @SerializedName("type_view")
     *
     */
    public function getInfosTypeView()
    {   
        $type = $this->getType();

        if($type instanceOf Type){

            switch ($type->getSlug()) {
                case 'code-promo':
                    return array(
                        'sub_type' => '',
                        'infos_view' => $this->getPromoCode()
                    );
                    break;
                case 'reduction':
                    return array(
                        'sub_type' => $this->getReductionType(),
                        'infos_view' => $this->getReduction()
                    );
                    break;
                case 'bon-plan':
                    return  array(
                        'sub_type' => '',
                        'infos_view' => $this->getPrice()
                    );
                    break;
            }
        }

        return $this->getPrice();
    }

    /**
     * @VirtualProperty
     * @SerializedName("users_like")
     *
     */
    public function getUsersLikesView()
    {   
        $usersLikes = $this->getUsersLikes();
        $result = array();
        $i = 0;
        if(null != $usersLikes){
            foreach ($usersLikes as $key => $value) {
                if($i > 10){
                    break;
                }
                array_push($result, $value->getUsername());
                $i++;
            }
        }

        return $result;
    }

    /**
     * @VirtualProperty
     * @SerializedName("already_like")
     *
     */
    public function getAlreadyLikeView()
    {   

        return $this->getAlreadyLike();
    }

    /**
     * @VirtualProperty
     * @SerializedName("already_share")
     *
     */
    public function getAlreadyShareView()
    {   

        return $this->getAlreadyShare();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Deal
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

 
    /**
     * Set type
     *
     * @param \KS\DealBundle\Entity\Type $type
     * @return Deal
     */
    public function setType(\KS\DealBundle\Entity\Type $type = null)
    {
        $this->type = $type;

        return $this;
    }


    /**
     * Set promoCode
     *
     * @param string $promoCode
     * @return Deal
     */
    public function setPromoCode($promoCode)
    {
        $this->promoCode = $promoCode;

        return $this;
    }

    /**
     * Get promoCode
     *
     * @return string 
     */
    public function getPromoCode()
    {
        return $this->promoCode;
    }

    /**
     * Set reduction
     *
     * @param integer $reduction
     * @return Deal
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return integer 
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Set reductionType
     *
     * @param string $reductionType
     * @return Deal
     */
    public function setReductionType($reductionType)
    {
        $this->reductionType = $reductionType;

        return $this;
    }

    /**
     * Get reductionType
     *
     * @return string 
     */
    public function getReductionType()
    {
        return $this->reductionType;
    }

    /**
     * Set expiration
     *
     * @param \DateTime $expiration
     * @return Deal
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get expiration
     *
     * @return \DateTime 
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Get type
     *
     * @return \KS\DealBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Deal
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /* 
     * Set alreadyLike
     *
     * @param boolean $alreadyLike
     * @return Deal
     */
    public function setAlreadyLike($alreadyLike)
    {
        $this->alreadyLike = $alreadyLike;

        return $this;
    }

    /**
     * Get alreadyLike
     *
     * @return boolean 
     */
    public function getAlreadyLike()
    {
        return $this->alreadyLike;
    }

    /* 
     * Set alreadyShare
     *
     * @param boolean $alreadyShare
     * @return Deal
     */
    public function setAlreadyShare($alreadyShare)
    {
        $this->alreadyShare = $alreadyShare;

        return $this;
    }

    /**
     * Get alreadyShare
     *
     * @return boolean 
     */
    public function getAlreadyShare()
    {
        return $this->alreadyShare;
    }
}
