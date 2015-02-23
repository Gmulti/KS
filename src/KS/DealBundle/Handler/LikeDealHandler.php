<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\RelationManyHandlerInterface;
use KS\UserBundle\Entity\User;


class LikeDealHandler implements RelationManyHandlerInterface{


	public function __construct(EntityManager $em, $entityClass)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->repository = $this->em->getRepository($this->entityClass);
	}

	public function delete($deal, User $user){
		$result = $this->repository->getLikeByUser($deal, $user);

    	if($result !== null){
    		$deal->removeUsersLike($user);
    		$user->removeDealsLike($deal);

    		$this->em->persist($deal);
    		$this->em->persist($user);
    		$this->em->flush();

    		return $deal;
    	}

    	return null;
	}

    public function post($deal, User $user){
    	$result = $this->repository->getLikeByUser($deal, $user);

    	if($result == null){
    		$deal->addUsersLike($user);
    		$user->addDealsLike($deal);

    		$this->em->persist($user);
    		$this->em->persist($deal);
    		$this->em->flush();

    		return $deal;
    	}

    	return null;
    }

}
