<?php

namespace KS\ServerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document
 */
class Scope
{

    /**
     * @MongoDB\Id(strategy="AUTO")
     */
    private $id;
    
    /**
     * @MongoDB\String
     */
    private $scope;

    /**
     * @MongoDB\String
     */
    private $description;

    /**
     * Set scope
     *
     * @param  string $scope
     * @return Scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Scope
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
}
