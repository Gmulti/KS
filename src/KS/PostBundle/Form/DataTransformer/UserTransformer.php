<?php

namespace KS\PostBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use KS\UserBundle\Document\User;

class UserTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    private $username;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ManagerRegistry $om)
    {
        $this->om = $om;
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

         
        $username = $this->om
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