<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\EntityRepository;
use KS\UserBundle\Entity\User;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\LikeRepositoryInterface;
use KS\DealBundle\Models\LikeEntityInterface;

class CommentRepository extends EntityRepository implements LikeRepositoryInterface
{


  	public function getLikeByUser(LikeEntityInterface $comment, User $user){

  		$qb = $this->_em->createQueryBuilder();

		$qb->select('c')
			->from('KSDealBundle:Comment','c')
			->join('c.usersLikesComment' , 'u')
			->addSelect('u')
			->where('u.id = :user')
			->setParameter('user', $user->getId())
			->andWhere('c.id = :comment')
			->setParameter('comment', $comment->getId());

		return $qb->getQuery()
				  ->getOneOrNullResult();
  	}

	public function getLikes($comment, $options = array()){
  		$qb = $this->_em->createQueryBuilder();

  		if (isset($options['username_only']) && $options['username_only']) {
			$qb->select('u.username');
		}
		else{
			$qb->select('u');
		}
		
		$qb->from('KSUserBundle:User','u')
			->join('u.commentsLikes' , 'c')
			->where('c.id = :comment')
			->setParameter('comment', $comment->getId());


		return $qb->getQuery()
				  ->getResult();
  	}

  
}