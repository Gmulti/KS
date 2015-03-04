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
 * @ORM\Entity(repositoryClass="KS\UserBundle\Entity\UserRelationRepository")
 * @ORM\Table(name="ks_user_relation")
 *
 * @ExclusionPolicy("all") 
 */
class UserRelation
{


    /**
	* @ORM\Id
	* @ORM\ManyToOne(targetEntity="KS\UserBundle\Entity\User", inversedBy="follows")
	*/
	protected $followedUser;

	/**
	* @ORM\Id
	* @ORM\ManyToOne(targetEntity="KS\UserBundle\Entity\User", inversedBy="subscribes")
	*/
	protected $subscribedUser;

    /**
     * @var date $created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Expose
     */
    protected $created;

    /**
     * @var date $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable
     * @Expose
     */
    protected $updated;



    /**
     * Set created
     *
     * @param \DateTime $created
     * @return UserRelation
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
     * @return UserRelation
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
     * Set followedUser
     *
     * @param \KS\UserBundle\Entity\User $followedUser
     * @return UserRelation
     */
    public function setFollowedUser(\KS\UserBundle\Entity\User $followedUser)
    {
        $this->followedUser = $followedUser;

        return $this;
    }

    /**
     * Get followedUser
     *
     * @return \KS\UserBundle\Entity\User 
     */
    public function getFollowedUser()
    {
        return $this->followedUser;
    }

    /**
     * Set subscribedUser
     *
     * @param \KS\UserBundle\Entity\User $subscribedUser
     * @return UserRelation
     */
    public function setSubscribedUser(\KS\UserBundle\Entity\User $subscribedUser)
    {
        $this->subscribedUser = $subscribedUser;

        return $this;
    }

    /**
     * Get subscribedUser
     *
     * @return \KS\UserBundle\Entity\User 
     */
    public function getSubscribedUser()
    {
        return $this->subscribedUser;
    }
}
