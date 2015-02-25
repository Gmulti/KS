<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\RelationManyHandlerInterface;
use KS\DealBundle\Models\LikeEntityInterface;
use KS\UserBundle\Entity\User;


class LikeCommentHandler implements RelationManyHandlerInterface{


	public function __construct(EntityManager $em, $entityClass)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->repository = $this->em->getRepository($this->entityClass);
	}

	public function delete(LikeEntityInterface $comment, User $user){
		$result = $this->repository->getLikeByUser($comment, $user);

    	if($result !== null){
    		$comment->removeUsersLikesComment($user);
            $comment->setNbUsersLikes($comment->getNbUsersLikes()-1);
            $user->removeCommentsLike($comment);

    		$this->em->persist($comment);
    		$this->em->persist($user);
    		$this->em->flush();

    		return $comment;
    	}

    	return null;
	}

    public function post(LikeEntityInterface $comment, User $user){
    	$result = $this->repository->getLikeByUser($comment, $user);

    	if($result == null){
    		$comment->addUsersLikesComment($user);
            $comment->setNbUsersLikes($comment->getNbUsersLikes()+1);
    		$user->addCommentsLike($comment);

    		$this->em->persist($user);
    		$this->em->persist($comment);
    		$this->em->flush();

    		return $comment;
    	}

    	return null;
    }

}
