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
     * @ORM\Column(type="string")
     * @Expose()
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="KS\UserBundle\Entity\User", inversedBy="medias")
     * @Expose()
     */
    protected $user;

    /**
     * @Expose()
     * @ORM\ManyToOne(targetEntity="KS\DealBundle\Entity\Deal", inversedBy="medias")
     */
    protected $deal;

    /**
     * @ORM\Column(type="boolean")
     * @Expose()
     */
    protected $userProfile;

    /**
     * @ORM\Column(type="string")
     */
    protected $path;

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
     * @ORM\Column(type="datetime")
     */
    protected $deletedAt;

    private $filenameForRemove;

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

        if (null !== $this->file) {
            $this->path = sha1(uniqid(mt_rand(), true)). '.' .$this->file->getClientOriginalExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        $this->file->move($this->getUploadRootDir(), $this->path);
        unset($this->file);
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
     * Set user
     *
     * @param KS\UserBundle\Entity\User $user
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return KS\UserBundle\Entity\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    public function removeUser(){
        $this->user = null;
        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get path
     *
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setFile(UploadedFile $file){
        $this->file = $file;
        return $this;
    }

    /**
     * Set userProfile
     *
     * @param boolean $userProfile
     * @return self
     */
    public function setUserProfile($userProfile)
    {
        $this->userProfile = $userProfile;
        return $this;
    }

    /**
     * Get userProfile
     *
     * @return boolean $userProfile
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }
}
