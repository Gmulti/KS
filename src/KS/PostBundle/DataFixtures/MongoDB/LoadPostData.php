<?php

namespace KS\PostBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KS\PostBundle\Document\Post;

class LoadPostData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /*
        $post = new Post();
        $post->setContent('dummy post');
        $post->setRating(1.0);
        $manager->persist($post);
        $manager->flush();
        */
    }
}