<?php

namespace KS\DealBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use KS\DealBundle\Entity\Category;

class CategoryTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @param ObjectManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return string
     */
    public function transform($category)
    {
        if (null === $category) {
            return "";
        }

        return $category;
    }

    public function reverseTransform($category)
    {

   
        return $category;
    }

    public function getName()
    {
        return 'category_selector';
    }
}