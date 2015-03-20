<?php

namespace KS\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KS\UserBundle\Entity\User as User;
use KS\DealBundle\Entity\Deal as Deal;
use Gedmo\Mapping\Annotation as Gedmo;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Hateoas\Configuration\Annotation as Hateoas;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity
 * @ORM\Table(name="ks_media")
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Serializer\XmlRoot("media")
 *
 * @ExclusionPolicy("all") 
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="KS\UserBundle\Entity\User", inversedBy="medias", cascade={"persist"})
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="KS\DealBundle\Entity\Deal", inversedBy="medias", cascade={"persist"})
     */
    protected $deal;

    /**
     * @ORM\ManyToOne(targetEntity="KS\DealBundle\Entity\Comment", inversedBy="medias", cascade={"persist"})
     */
    protected $comment;

    /**
     * @ORM\Column(type="boolean")
     * @Expose()
     */
    protected $userProfile;

    /**
     * @ORM\Column(type="string")
     * @Expose()
     */
    protected $path;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $url;    

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Expose()
     */
    protected $thumbnailsUrl;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;


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
     * @var date $updated
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    private $filenameForRemove;

    public function __construct(){
        $this->userProfile = false;
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
     * Set deal
     *
     * @return self
     */
    public function setDeal(\KS\DealBundle\Entity\Deal $deal)
    {
        $this->deal = $deal;
        return $this;
    }

    /**
     * Get deal
     *
     * @return KS\DealBundle\Entity\Post $deal
     */
    public function getDeal()
    {
        return $this->deal;
    }

    public function removePost(){
        $this->deal = null;
        return $this;
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


    public function getWebPath()
    {
        return null === $this->path ? null : $this->getWebPathDir().'/'.$this->path;
    }

    public function getPathImagine(){
        return null === $this->path ? null : $this->getBundlesPath() . '/' . $this->path;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../Resources/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'public/images';
    }

    protected function getWebPathDir(){
        return $_SERVER['HTTP_HOST'] . $this->getBundlesPath();
    }

    protected function getBundlesPath(){
        return '/bundles/ksmedia/images';
    }


     /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {

        if (isset($this->file) && null !== $this->file) {
            $this->path = sha1(uniqid(mt_rand(), true)). '.' .$this->file->getClientOriginalExtension();
            $this->url  = $this->getWebPath();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if(isset($this->file)){
            $this->file->move($this->getUploadRootDir(), $this->path);
            unset($this->file);
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }


    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }

    public function getAbsolutePath()
    {

        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }



    /**
     * Set userProfile
     *
     * @param boolean $userProfile
     * @return Media
     */
    public function setUserProfile($userProfile)
    {
        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * Get userProfile
     *
     * @return boolean 
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set user
     *
     * @param \KS\UserBundle\Entity\User $user
     * @return Media
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

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Set comment
     *
     * @param \KS\DealBundle\Entity\Deal $comment
     * @return Media
     */
    public function setComment(\KS\DealBundle\Entity\Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \KS\DealBundle\Entity\Deal 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Media
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
     * Set thumbnailsUrl
     *
     * @param array $thumbnailsUrl
     * @return Media
     */
    public function setThumbnailsUrl($thumbnailsUrl)
    {
        $this->thumbnailsUrl = $thumbnailsUrl;

        return $this;
    }

    /**
     * Get thumbnailsUrl
     *
     * @return array 
     */
    public function getThumbnailsUrl()
    {
        return $this->thumbnailsUrl;
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

    /**
     * @VirtualProperty
     * @SerializedName("deal")
     *
     */
    public function getDealSerialize()
    {       
        $deal = $this->getDeal();
        if(!empty($deal)){
            return $deal->getId();
        }
        
        return null;
    }

    /**
     * @VirtualProperty
     * @SerializedName("comment")
     *
     */
    public function getCommentSerialize()
    {   
        $comment = $this->getComment();
        if(!empty($comment)){
            return $comment->getId();
        }

        return null;
    }

}
