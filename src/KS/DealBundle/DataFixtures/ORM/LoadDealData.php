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
        $deal->addCategory($this->getReference('cat1'));
        $deal->addType($this->getReference('code-promo'));

        $dealAdmin = new Deal();
        $dealAdmin->setContent('dummy deal admin');
        $dealAdmin->setPrice(15);
        $dealAdmin->setUser($this->getReference('admin-test'));
        $dealAdmin->addCategory($this->getReference('cat11'));
        $dealAdmin->addCategory($this->getReference('cat22'));
        $dealAdmin->addType($this->getReference('bon-plan'));
        $dealAdmin->addUsersLike($this->getReference('user-test'));
        $dealAdmin->setNbUsersLikes(1);

        $dealGratuit = new Deal();
        $dealGratuit->setContent('dummy deal gratuit');
        $dealGratuit->setPrice(0);
        $dealGratuit->setUser($this->getReference('user-test'));
        $dealGratuit->addCategory($this->getReference('cat2'));
        $dealGratuit->addType($this->getReference('gratuit'));


        $manager->persist($deal);
        $manager->persist($dealAdmin);
        $manager->persist($dealGratuit);
        $manager->flush();

        $this->addReference('deal-promo', $deal);

      
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 20;
    }
}