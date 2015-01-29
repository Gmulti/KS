<?php

namespace KS\MediaBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use KS\PageBundle\Document\Page as Page;
use KS\UserBundle\Document\User as User;
use KS\PostBundle\Document\Post as Post;
use Gedmo\Mapping\Annotation as Gedmo;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Hateoas\Configuration\Annotation as Hateoas;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @MongoDB\Document
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Serializer\XmlRoot("media")
 *
 * @MongoDB\HasLifecycleCallbacks
 * @Hateoas\Relation("user", 
 *     href = "expr('/users/' ~ object.getUser().getUsername() )",
 * )
 * @Hateoas\Relation("post", 
 *     href = "expr('/posts/' ~ object.getPost().getId() )",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getPost() === null)")
 * )
 *
 * @ExclusionPolicy("all") 
 */
class Media
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\ReferenceOne(
     *    targetDocument="KS\UserBundle\Document\User", 
     *    mappedBy="medias",
     *    cascade={"all"}
     * )
     */
    protected $user;

    /**
     * @MongoDB\ReferenceOne(
     *    targetDocument="KS\PostBundle\Document\Post", 
     *    mappedBy="media", 
     *    cascade={"all"} 
     * )
     */
    protected $post;

    /**
     * @MongoDB\Boolean
     * @Expose()
     */
    protected $userProfile;

    /**
     * @MongoDB\String
     * @Expose()
     */
    protected $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;


    /**
     * @var date $created
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     * @Expose()
     */
    protected $created;

    /**
     * @var date $updated
     *
     * @MongoDB\Date
     * @Gedmo\Timestampable
     * @Expose()
     */
    protected $updated;

    /**
     * @var date $updated
     *
     * @MongoDB\Date
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
     * Set post
     *
     * @param KS\PostBundle\Document\Post $post
     * @return self
     */
    public function setPost(\KS\PostBundle\Document\Post $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * Get post
     *
     * @return KS\PostBundle\Document\Post $post
     */
    public function getPost()
    {
        return $this->post;
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

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../Resources/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'public/images';
    }

    protected function getWebPathDir(){
        return $_SERVER['HTTP_HOST'] . '/bundles/ksmedia/images';
    }


     /**
     * @MongoDB\PrePersist()
     * @MongoDB\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->path = sha1(uniqid(mt_rand(), true)). '.' .$this->file->guessExtension();
        }
    }

    /**
     * @MongoDB\PostPersist()
     * @MongoDB\PostUpdate()
     */
    public function upload()
    {
        $this->file->move($this->getUploadRootDir(), $this->path . '.' . $this->file->guessExtension());

        unset($this->file);
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }


    /**
     * @MongoDB\PostRemove()
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
