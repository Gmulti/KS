<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;

use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\RelationManyHandlerInterface;
use KS\DealBundle\Models\ManyEntityInterface;
use KS\DealBundle\Models\ManyTypeInterface;
use KS\DealBundle\Models\LikeDealManyType;
use KS\DealBundle\Models\LikeCommentManyType;
use KS\DealBundle\Models\ShareDealManyType;

use KS\UserBundle\Models\FollowUserManyType;
use KS\UserBundle\Entity\User;
use KS\UserBundle\Entity\UserRelation;


class ManyHandler implements RelationManyHandlerInterface{


	public function __construct(EntityManager $em, $entityClass, $typeMany)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->repository = $this->em->getRepository($this->entityClass);
	    $this->typeMany = new $typeMany();
	}

	public function delete(ManyEntityInterface $entityMany, User $user, $options = array()){

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
	    	elseif($this->typeMany instanceOf FollowUserManyType){
	    		$this->removeFollowUser($entityMany, $user, $result);
	    	}

    		$this->em->persist($entityMany);
    		$this->em->persist($user);
    		$this->em->flush();

    		return $entityMany;
    	}

    	return null;
	}

	public function get(ManyEntityInterface $entityMany, $options){
		return $this->repository->getNbManyRelation($entityMany, $this->typeMany, $options);
	}

    public function post(ManyEntityInterface $entityMany, User $user, $options = array()){

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
	    	elseif($this->typeMany instanceOf FollowUserManyType){
	    		$this->addFollowUser($entityMany, $user);
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
		$user->addDealsLike($deal);

    }

    private function addCommentLike($comment, $user){

		$comment->addUsersLikesComment($user);
		$user->addCommentsLike($comment);

    }

    private function addShareDeal($deal, $user){
		$deal->addUsersShared($user);
		$user->addDealsShared($deal);
    }

    private function removeLikeDeal($deal, $user){
	    $deal->removeUsersLike($user);
		$user->removeDealsLike($deal);
    }

    private function removeCommentLike($comment, $user){
		$comment->removeUsersLikesComment($user);
        $user->removeCommentsLike($comment);
    }

    private function removeShareDeal($deal, $user){
		$deal->removeUsersShared($user);
		$user->removeDealsShared($deal);
    }

    private function addFollowUser($userFollowed, $userSubscribe){
    	$userRelation = new UserRelation();
    	$userRelation->setFollowedUser($userFollowed);
    	$userRelation->setSubscribedUser($userSubscribe);

    	$userFollowed->addFollower($userRelation);
		$userFollowed->setNbFollowers($userFollowed->getNbFollowers()+1);

		$userSubscribe->addSubscribe($userRelation);
		$userSubscribe->setNbSubscribes($userSubscribe->getNbSubscribes()+1);
		$this->em->persist($userRelation);
    }

    private function removeFollowUser($userFollowed, $userFollow, $userRelation){

    	$userFollowed->removeFollower($userRelation);
		$userFollowed->setNbFollowers($userFollowed->getNbFollowers()-1);

		$userFollow->removeSubscribe($userRelation);
		$userFollow->setNbSubscribes($userFollow->getNbSubscribes()-1);
		$this->em->remove($userRelation);
    }
}
