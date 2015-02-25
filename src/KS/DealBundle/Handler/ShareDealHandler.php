<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\RelationManyHandlerInterface;
use KS\DealBundle\Models\LikeEntityInterface;
use KS\UserBundle\Entity\User;


class ShareDealHandler implements RelationManyHandlerInterface{


	public function __construct(EntityManager $em, $entityClass)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->repository = $this->em->getRepository($this->entityClass);
	}

	public function post(Deal $deal, User $user){
		$result = $this->repository->getShareByUser($deal, $user);

    	if($result !== null){
            $deal->addUsersShared($user);
    		$deal->setNbUsersLikes($deal->getNbUsersShared()-1);
    		$user->addDealsShared($deal);

    		$this->em->persist($deal);
    		$this->em->persist($user);
    		$this->em->flush();

    		return $deal;
    	}

    	return null;
	}


}
