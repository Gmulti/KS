<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\EntityRepository;
use KS\UserBundle\Entity\User;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\LikeRepositoryInterface;

class CommentRepository extends EntityRepository implements LikeRepositoryInterface
{


  	public function getLikeByUser($comment, User $user){

  		$qb = $this->_em->createQueryBuilder();

		$qb->select('d')
			->from('KSDealBundle:Comment','c')
			->join('c.likesComment' , 'u')
			->addSelect('u')
			->where('u.id = :user')
			->setParameter('user', $user->getId())
			->andWhere('c.id = :comment')
			->setParameter('comment', $comment->getId());

		return $qb->getQuery()
				  ->getOneOrNullResult();
  	}

  
}