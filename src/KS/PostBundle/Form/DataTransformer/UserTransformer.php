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

    /**
     * @param ObjectManager $om
     */
    public function __construct(ManagerRegistry $om)
    {
        $this->om = $om;
    }

    /**
     * @return string
     */
    public function transform($username)
    {
           var_dump($username);
        if (null === $username) {
            return "";
        }

        return $username;
    }

    public function reverseTransform($username)
    {

        var_dump($username);
        if (!$username) {
            return null;
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