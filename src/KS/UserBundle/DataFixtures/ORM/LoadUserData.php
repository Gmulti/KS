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
        $userTest = new User();
        $userTest->setEmail('test@ks.com');
        $userTest->setUsername('test');
        $userTest->setFirstname('de test');
        $userTest->setLastname('Compte');
        $userTest->setEnabled(true);
        $userTest->setRoles(array('ROLE_USER'));
        $userTest->setPlainPassword('test');

        $adminTest = new User();
        $adminTest->setEmail('testadmin@ks.com');
        $userTest->setUsername('testadmin');
        $adminTest->setFirstname('de test admin');
        $adminTest->setLastname('Compte');
        $adminTest->setEnabled(true);
        $adminTest->setRoles(array('ROLE_ADMIN'));
        $adminTest->setPlainPassword('testadmin');

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user-test', $userTest);
        $this->addReference('admin-test', $adminTest);
    }

     /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}