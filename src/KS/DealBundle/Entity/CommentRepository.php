<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\EntityRepository;
use KS\UserBundle\Entity\User;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\ManyRepositoryInterface;
use KS\DealBundle\Models\ManyEntityInterface;
use KS\DealBundle\Models\ManyTypeInterface;
use KS\DealBundle\Models\LikeCommentManyType;

class CommentRepository extends EntityRepository implements ManyRepositoryInterface
{

	public function getManyByUser(ManyEntityInterface $entityMany, User $user, ManyTypeInterface $typeMany){

  		$qb = $this->_em->createQueryBuilder();

		$qb->select('c')
			->from('KSDealBundle:Comment','c');

		if ($typeMany instanceOf LikeCommentManyType) {
			$qb->join('c.usersLikesComment' , 'u');
		}
		
		$qb->addSelect('u')
			->where('u.id = :user')
			->setParameter('user', $user->getId())
			->andWhere('c.id = :comment')
			->setParameter('comment', $entityMany->getId());

		return $qb->getQuery()
				  ->getOneOrNullResult();
  	}


  	public function getNbManyRelation(ManyEntityInterface $entityMany, ManyTypeInterface $typeMany, $options = array()){
  		$qb = $this->_em->createQueryBuilder();

  		if (isset($options['username_only']) && $options['username_only']) {
			$qb->select('u.username');
		}
		else{
			$qb->select('u');
		}
		
		$qb->from('KSUserBundle:User','u');

		if ($typeMany instanceOf LikeCommentManyType) {
			$qb->join('u.commentsLikes' , 'c');
		}
			
	    $qb->where('c.id = :comment')
			->setParameter('comment', $entityMany->getId());


		return $qb->getQuery()
				  ->getResult();
  	}
  
}