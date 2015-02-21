<?php

namespace KS\DealBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;
use KS\DealBundle\Entity\Deal;

class DealTransformer implements DataTransformerInterface
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
     * Object => String
     */
    public function transform($deal)
    {   

        if (null === $deal) {
            return "";
        }

        return $deal->getId();
    }

    /**
     * String => Object
     */
    public function reverseTransform($deal)
    {

        if (!$deal && empty($this->deal) ) {
            return null;
        }

        $deal = $this->em
            ->getRepository('KSDealBundle:Deal')
            ->findOneById($deal);

        if (null === $deal) {
            throw new TransformationFailedException(sprintf(
                'Deal "%s" not exist',
                $deal
            ));
        }
         

        return $deal;
    }

    public function getName()
    {
        return 'deal_selector';
    }
}