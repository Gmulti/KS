<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\EntityRepository;
use KS\UserBundle\Entity\User;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\ManyRepositoryInterface;
use KS\DealBundle\Models\ManyEntityInterface;
use KS\DealBundle\Models\ManyTypeInterface;

class DealRepository extends EntityRepository implements ManyRepositoryInterface
{

	private $start_price;
	
	private $end_price;

	private function setParameterPrice($qb, $price, $orientation = 'start_price'){

		switch ($orientation) {
			case 'start_price':
				$this->start_price = $price;
				$qb->andWhere('d.price >= :start_price');
				$qb->setParameter('start_price', $price);
				break;
			
			case 'end_price':
				// Error. Start price can't be greater than end price
				if ($price <= $this->start_price) {
					return $qb;
				}

				$qb->andWhere('d.price <= :end_price');
				$qb->setParameter('end_price', $price);
				break;
		}

		return $qb;
	}

	public function getDealsWithOptions($options, $limit = 0, $offset = 10)
	{
    	$qb = $this->_em->createQueryBuilder();

		$qb->select('d')
			->from('KSDealBundle:Deal','d')
			->orderBy('d.updated', 'DESC')
			->where('d.deletedAt is null')
			->setFirstResult($offset)
			->setMaxResults($limit);

		foreach ($options as $key => $value) {
			switch ($key) {
				case 'start_price':
				case 'end_price':
					$qb = $this->setParameterPrice($qb, $value, $key);
					break;
				
			}
		}

		return $qb->getQuery()
				  ->getResult();
  	}

  	public function getManyByUser(ManyEntityInterface $entityMany, User $user, ManyTypeInterface $typeMany){

  		$qb = $this->_em->createQueryBuilder();

		$qb->select('d')
			->from('KSDealBundle:Deal','d');

		if ($typeMany instanceOf LikeDealManyType) {
			$qb->join('d.usersLikes' , 'u');
		}
		elseif ($typeMany instanceOf ShareDealManyType){
			$qb->join('d.usersShared' , 'u');
		}
			
		$qb->addSelect('u')
			->where('u.id = :user')
			->setParameter('user', $user->getId())
			->andWhere('d.id = :deal')
			->setParameter('deal', $entityMany->getId());

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

		if ($typeMany instanceOf LikeDealManyType) {
			$qb->join('u.dealsLikes' , 'd');
		}
		elseif ($typeMany instanceOf ShareDealManyType){
			$qb->join('u.dealsShared' , 'd');
		}
			
		$qb->where('d.id = :deal')
			->setParameter('deal', $entityMany->getId());


		return $qb->getQuery()
				  ->getResult();
  	}



  
}