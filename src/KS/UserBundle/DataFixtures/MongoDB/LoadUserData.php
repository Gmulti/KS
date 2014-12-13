<?php

namespace KS\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KS\UserBundle\Document\User;

class LoadPostData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('test@ks.com');
        $user->setFirstname('test');
        $user->setLastname('test');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPlainPassword('test');
        $manager->persist($user);
        $manager->flush();
    }
}