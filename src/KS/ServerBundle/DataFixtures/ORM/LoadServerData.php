<?php

namespace KS\ServerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

use KS\ServerBundle\Entity\Client;

class LoadServerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	/**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    	$clientManager = $this->container->get('oauth2.client_manager');

   	 	$clientDesktop = $clientManager->createClient(
            '317b47172',
            array('dev.komunitystore.dev'),
            array('password','refresh_token'),
            'desktop'
        );

        // $clientIOS = $clientManager->createClient(
        //     '317b47172',
        //     'dev.komunitystore.dev',
        //     'password',
        //     'desktop'
        // );

        // $manager->persist($user);
        // $manager->flush();
    }

     /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}