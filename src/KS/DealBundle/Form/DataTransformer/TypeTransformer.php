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


    public function transform($types)
    {
        if (null === $types) {
            return "";
        }
        
        // $typesArray = new \Doctrine\Common\Collections\ArrayCollection();     
        
        // foreach ($types as $key => $value) {
        //     if (condition) {
        //         # code...
        //     }
        // }

        return $types;
    }

    public function reverseTransform($types)
    {
     
        $typesArray = new \Doctrine\Common\Collections\ArrayCollection();  

        if (!is_array($types)):
            throw new TransformationFailedException(sprintf(
                'Format not valid!'
            ));
        endif;


        foreach ($types as $type) {

            $typeFind = $this->em
                             ->getRepository('KSDealBundle:Type')
                             ->findOneBySlug($type);

            if (null === $typeFind) {
                throw new TransformationFailedException(sprintf(
                    'Type "%s" does not exist!',
                    $type
                ));
            }

            $typesArray->add($typeFind);
        }
        
        return $typesArray;

    }

    public function getName()
    {
        return 'type_selector';
    }
}