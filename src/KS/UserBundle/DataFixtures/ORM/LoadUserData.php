<?php

namespace KS\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use KS\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail('thomasdeneulin@gmail.com');
        $user1->setUsername('thomas');
        $user1->setFirstname('Thomas');
        $user1->setLastname('DENEULIN');
        $user1->setEnabled(true);
        $user1->setRoles(array('ROLE_ADMIN'));
        $user1->setPlainPassword('thomas');

        $user2 = new User();
        $user2->setEmail('nediug@hotmail.com');
        $user2->setUsername('guillaume');
        $user2->setFirstname('Guillaume');
        $user2->setLastname('DENEULIN');
        $user2->setEnabled(true);
        $user2->setRoles(array('ROLE_ADMIN'));
        $user2->setPlainPassword('guillaume');

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();

        $this->addReference('admin-1', $user1);
        $this->addReference('admin-2', $user2);
    }

     /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}