<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\RelationManyHandlerInterface;
use KS\DealBundle\Models\ManyEntityInterface;
use KS\DealBundle\Models\ManyTypeInterface;
use KS\UserBundle\Entity\User;


class ManyHandler implements RelationManyHandlerInterface{


	public function __construct(EntityManager $em, ManyEntityInterface $entityClass, ManyTypeInterface $typeMany)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->repository = $this->em->getRepository($this->entityClass);
	    $this->typeMany = $typeMany;
	}

	public function delete(ManyEntityInterface $entityMany, User $user){
		$result = $this->repository->getManyByUser($entityMany, $user, $this->typeMany);

    	if($result !== null){

           	if ($this->typeMany instanceOf LikeDealManyType) {
	    		$this->removeLikeDeal($entityMany, $user);
	    	}
	    	elseif ($this->typeMany instanceOf LikeCommentManyType) {
	    		$this->removeCommentLike($entityMany, $user);
	    	}
	    	elseif ($this->typeMany instanceOf ShareDealManyType) {
	    		$this->removeShareDeal($entityMany, $user);
	    	}

    		$this->em->persist($deal);
    		$this->em->persist($user);
    		$this->em->flush();

    		return $deal;
    	}

    	return null;
	}

    public function post(ManyEntityInterface $entityMany, User $user){

    	$result = $this->repository->getManyByUser($entityMany, $user, $this->typeMany);

    	if (null == $result) {
    		if ($this->typeMany instanceOf LikeDealManyType) {
	    		$this->addLikeDeal($entityMany, $user);
	    	}
	    	elseif ($this->typeMany instanceOf LikeCommentManyType) {
	    		$this->addCommentLike($entityMany, $user);
	    	}
	    	elseif ($this->typeMany instanceOf ShareDealManyType) {
	    		$this->addShareDeal($entityMany, $user);
	    	}
	    		
    		$this->em->persist($user);
    		$this->em->persist($entityMany);
    		$this->em->flush();

    		return $entityMany;
    	}
    	

    	return null;
    }

    private function addLikeDeal($deal, $user){

    	$deal->addUsersLike($user);
        $deal->setNbUsersLikes($deal->getNbUsersLikes()+1);
		$user->addDealsLike($deal);

    }

    private function addCommentDeal($comment, $user){

		$comment->addUsersLikesComment($user);
        $comment->setNbUsersLikes($comment->getNbUsersLikes()+1);
		$user->addCommentsLike($comment);

    }

    private function addShareDeal($deal, $user){
		$deal->addUsersShared($user);
		$deal->setNbUsersLikes($deal->getNbUsersShared()-1);
		$user->addDealsShared($deal);
    }

    private function removeLikeDeal($deal, $user){
	    $deal->removeUsersLike($user);
		$deal->setNbUsersLikes($deal->getNbUsersLikes()-1);
		$user->removeDealsLike($deal);
    }

    private function removeCommentLike($comment, $user){
		$comment->removeUsersLikesComment($user);
        $comment->setNbUsersLikes($comment->getNbUsersLikes()-1);
        $user->removeCommentsLike($comment);
    }
}
