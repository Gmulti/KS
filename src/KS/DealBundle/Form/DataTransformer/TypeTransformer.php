<?php

namespace KS\DealBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use KS\DealBundle\Entity\Type;

class TypeTransformer implements DataTransformerInterface
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


    public function transform($type)
    {
        if (null === $type) {
            return "";
        }
        
        // $typeArray = new \Doctrine\Common\Collections\ArrayCollection();     
        
        // foreach ($type as $key => $value) {
        //     if (condition) {
        //         # code...
        //     }
        // }


        return $type;
    }

    public function reverseTransform($type)
    {
       
        $type = $this->em
                 ->getRepository('KSDealBundle:Type')
                 ->findOneBySlug($type);
                 
        return $type;

    }

    public function getName()
    {
        return 'type_selector';
    }
}