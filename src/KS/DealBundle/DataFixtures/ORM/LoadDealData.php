<?php

namespace KS\DealBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use KS\DealBundle\Entity\Deal;

class LoadDealData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
        $deal = new Deal();
        $deal->setContent('dummy deal');
        $deal->setPrice(10);
        $deal->setUser($this->getReference('user-test'));
        

        $dealAdmin = new Deal();
        $dealAdmin->setContent('dummy deal admin');
        $dealAdmin->setPrice(10);
        $dealAdmin->setUser($this->getReference('admin-test'));

        $manager->persist($deal);
        $manager->persist($dealAdmin);
        $manager->flush();
        
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}