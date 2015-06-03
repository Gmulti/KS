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
        $deal->setTitle('Title dummy deal');
        $deal->setContent('dummy deal');
        $deal->setPrice(10);
        $deal->setUser($this->getReference('thomas'));
        $deal->setPromoCode("ABC_CODE_PROMO");
        // $deal->addCategory($this->getReference('cat1'));
        $deal->setType($this->getReference('code-promo'));

        $dealAdmin = new Deal();
        $dealAdmin->setTitle('Title dummy deal admin');
        $dealAdmin->setContent('dummy deal admin');
        $dealAdmin->setPrice(15);
        $dealAdmin->setUser($this->getReference('tanguy'));
        // $dealAdmin->addCategory($this->getReference('cat11'));
        // $dealAdmin->addCategory($this->getReference('cat22'));
        $dealAdmin->setType($this->getReference('bon-plan'));
        // $dealAdmin->addUsersLike($this->getReference('thomas'));
        // $dealAdmin->setNbUsersLikes(1);

        $dealGratuit = new Deal();
        $dealGratuit->setTitle('Title dummy deal gratuit');
        $dealGratuit->setContent('dummy deal gratuit');
        $dealGratuit->setPrice(50);
        $dealGratuit->setUser($this->getReference('thomas'));
        // $dealGratuit->addCategory($this->getReference('cat2'));
        $dealGratuit->setType($this->getReference('reduction'));
        $dealGratuit->setReductionType('pourcent');
        $dealGratuit->setReduction(10);


        $dealLocalisation = new Deal();
        $dealLocalisation->setTitle('Title dummy deal localisation');
        $dealLocalisation->setContent('dummy deal localisation');
        $dealLocalisation->setPrice(15);
        $dealLocalisation->setUser($this->getReference('thomas'));
        // $dealLocalisation->addCategory($this->getReference('cat2'));
        $dealLocalisation->setType($this->getReference('reduction'));
        $dealLocalisation->setReductionType('euros');
        $dealLocalisation->setReduction(5);
        $dealLocalisation->setLng(4.854993);
        $dealLocalisation->setLat(45.75537);



        $manager->persist($deal);
        $manager->persist($dealAdmin);
        $manager->persist($dealGratuit);
        $manager->persist($dealLocalisation);
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