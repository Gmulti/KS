<?php

namespace KS\DealBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;
use KS\UserBundle\Entity\User;

class UserTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    private $username;

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
    public function transform($username)
    {   

        if (null === $username) {
            return "";
        }

        return $this->username = $username->getUsername();
    }

    /**
     * String => Object
     */
    public function reverseTransform($username)
    {

        if (!$username && empty($this->username) ) {
            return null;
        }
        elseif(!$username && !empty($this->username)){
            $username = $this->username;
        }

        $username = $this->em
            ->getRepository('KSUserBundle:User')
            ->findOneByUsername($username);

        if (null === $username) {
            throw new TransformationFailedException(sprintf(
                'User "%s" not exist',
                $username
            ));
        }
         

        return $username;
    }

    public function getName()
    {
        return 'user_selector';
    }
}