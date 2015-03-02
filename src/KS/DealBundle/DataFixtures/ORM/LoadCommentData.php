<?php

namespace KS\DealBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use KS\DealBundle\Entity\Comment;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
        for ($i=0; $i < 20 ; $i++) { 
            $comment = new Comment();
            $comment->setContent('Commentaire numero '. $i);
            if ($i%2 == 0) {
                $comment->setUser($this->getReference('user-test'));
            }
            else{
                $comment->setUser($this->getReference('admin-test'));
            }
            $comment->setDeal($this->getReference('deal-promo'));
            $manager->persist($comment);
        }
       
        $manager->flush();

      
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 25;
    }
}