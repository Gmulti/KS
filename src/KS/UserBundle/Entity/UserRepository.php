<?php

namespace KS\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

use KS\UserBundle\Entity\User;
use KS\DealBundle\Entity\Deal;

use KS\DealBundle\Models\ManyEntityInterface;
use KS\DealBundle\Models\ManyTypeInterface;

use KS\UserBundle\Models\FollowUserManyType;
use KS\DealBundle\Models\ManyRepositoryInterface;

class UserRepository extends EntityRepository implements ManyRepositoryInterface
{

	public function findByIdOrSlug($value){
		$cast = (int)$value;

		$qb = $this->_em->createQueryBuilder();

  		// String
  		if($cast === 0){
  			$qb->select('u')
				->from('KSUserBundle:User','u')
				->where('u.username = :user')
				->setParameter('user', $value);
  		}
  		else{
  			$qb->select('u')
				->from('KSUserBundle:User','u')
				->where('u.id = :user')
				->setParameter('user', $cast);
  		}

  		return $qb->getQuery()
				  ->getOneOrNullResult();
	}

  	public function getManyByUser(ManyEntityInterface $entityMany, User $user, ManyTypeInterface $typeMany){

  		$qb = $this->_em->createQueryBuilder();

		$qb->select('u')
			->from('KSUserBundle:UserRelation','u');

		
		if ($typeMany instanceOf FollowUserManyType) {
			$qb->join('u.subscribedUser' , 's');
			$qb->join('u.followedUser', 'f');
		}
	
		$qb->where('s.id = :user')
			->setParameter('user', $user->getId())
			->andWhere('f.id = :entityMany')
			->setParameter('entityMany', $entityMany->getId());


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

		if ($typeMany instanceOf FollowUserManyType && isset($options['followers']) && $options['followers'] == 1) {
			$qb->join('u.followers' , 'f');
			$qb->where('f.id = :user');
		}
		else if ($typeMany instanceOf FollowUserManyType && isset($options['subscribes']) && $options['subscribes'] == 1) {
			$qb->join('u.subscribes' , 's');
			$qb->where('s.id = :user');
		}
			
		
		$qb->setParameter('user', $entityMany->getId());


		return $qb->getQuery()
				  ->getResult();
  	}
  
}