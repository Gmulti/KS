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
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=64)
     */
    private $title;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="slug", type="string", length=128)
     */
    private $slug;

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

}
