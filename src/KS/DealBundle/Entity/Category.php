<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Doctrine\Common\Collections\ArrayCollection;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;



/**
 * @ORM\Entity
 * @ORM\Table(name="ks_category")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 *
 * @Gedmo\Tree(type="nested")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * 
 * @ExclusionPolicy("all") 
 */
class Category
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string", length=64)
     * @Expose()
     */
    protected $title;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     * @Expose()
     */
    protected $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     * @Expose()
     */
    protected $rgt;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     * @Expose()
     */
    protected $lvl;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @Expose()
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @Expose()
     */
    protected $children;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=128)
     * @Expose()
     */
    protected $slug;

    /**
     * @ORM\ManyToMany(targetEntity="KS\DealBundle\Entity\Deal", cascade={"all"}, mappedBy="categories")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $deals;

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

    public function __construct(){
         $this->categories = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Category
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
     * Set lft
     *
     * @param integer $lft
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Set parent
     *
     * @param \KS\DealBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\KS\DealBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \KS\DealBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \KS\DealBundle\Entity\Category $children
     * @return Category
     */
    public function addChild(\KS\DealBundle\Entity\Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \KS\DealBundle\Entity\Category $children
     */
    public function removeChild(\KS\DealBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add deals
     *
     * @param \KS\DealBundle\Entity\Deal $deals
     * @return Category
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

    public function __toString(){
        return $this->title;
    }
}
