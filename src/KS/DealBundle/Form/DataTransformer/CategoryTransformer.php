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


    public function transform($categories)
    {
        if (null === $categories) {
            return "";
        }
        
        // $categoriesArray = new \Doctrine\Common\Collections\ArrayCollection();     
        
        // foreach ($categories as $key => $value) {
        //     if (condition) {
        //         # code...
        //     }
        // }

        return $categories;
    }

    public function reverseTransform($categories)
    {
     
        $categoriesArray = new \Doctrine\Common\Collections\ArrayCollection();  

        if (!is_array($categories)):
            throw new TransformationFailedException(sprintf(
                'Format not valid!'
            ));
        endif;


        foreach ($categories as $cat) {

            $categoryFind = $this->em
                                 ->getRepository('KSDealBundle:Category')
                                 ->findOneBySlug($cat);

            if (null === $categoryFind) {
                throw new TransformationFailedException(sprintf(
                    'Categorie "%s" does not exist!',
                    $cat
                ));
            }

            $categoriesArray->add($categoryFind);
        }
        
        return $categoriesArray;

    }

    public function getName()
    {
        return 'category_selector';
    }
}