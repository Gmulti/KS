<?php

namespace KS\DealBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

use KS\DealBundle\Entity\Type;

class LoadTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $code = new Type();
        $code->setTitle('Code promo');

        $bonPlan = new Type();
        $bonPlan->setTitle('Bon plan');

        $gratuit = new Type();
        $gratuit->setTitle('Réduction');

        $manager->persist($code);
        $manager->persist($bonPlan);
        $manager->persist($gratuit);
        $manager->flush();

        $this->addReference('code-promo', $code);
        $this->addReference('bon-plan', $bonPlan);
        $this->addReference('reduction', $gratuit);
    }


    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
}