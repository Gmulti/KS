<?php

namespace KS\DealBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Entity\Category;

class LoadDealData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadCategory($manager);
        $this->loadDeal($manager);
        $this->loadComment($manager);
        
    }

    private function loadComment($manager){

    }

    private function loadCategory($manager){
        $cat1 = new Category();
        $cat1->setTitle('Catégorie 1');

        $cat11 = new Category();
        $cat11->setTitle('Catégorie 1_1');
        $cat11->setParent($cat1);

        $cat2 = new Category();
        $cat2->setTitle('Catégorie 2');

        $cat22 = new Category();
        $cat22->setTitle('Catégorie 2_2');
        $cat22->setParent($cat2);

        $manager->persist($cat1);
        $manager->persist($cat11);
        $manager->persist($cat2);
        $manager->persist($cat22);
        $manager->flush();

        $this->addReference('cat1', $cat1);
        $this->addReference('cat11', $cat11);
        $this->addReference('cat2', $cat2);
        $this->addReference('cat22', $cat22);
    }

    private function loadDeal($manager){
        $deal = new Deal();
        $deal->setContent('dummy deal');
        $deal->setPrice(10);
        $deal->setUser($this->getReference('user-test'));
        $deal->addCategory($this->getReference('cat1'));

        $dealAdmin = new Deal();
        $dealAdmin->setContent('dummy deal admin');
        $dealAdmin->setPrice(10);
        $dealAdmin->setUser($this->getReference('admin-test'));
        $dealAdmin->addCategory($this->getReference('cat11'));
        $dealAdmin->addCategory($this->getReference('cat22'));

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