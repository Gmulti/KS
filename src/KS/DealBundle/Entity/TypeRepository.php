<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TypeRepository extends EntityRepository
{
	public function getDealsByType($type, $limit = 0, $offset = 10)
	{
    	$qb = $this->_em->createQueryBuilder();

		$qb->select('d')
			->from('KSDealBundle:Deal','d')
			->join('d.type' , 'c')
			->addSelect('c')
			->where('c.id = :type')
			->setParameter('type', $type->getId())
			->orderBy('d.updated', 'DESC')
			->setFirstResult($offset)
			->setMaxResults($limit);

		return $qb->getQuery()
				  ->getResult();
  	}

  	public function findByIdOrSlug($value){
  		$cast = (int)$value;

			$qb = $this->_em->createQueryBuilder();

  		// String
  		if($cast === 0){
  			$qb->select('c')
				->from('KSDealBundle:Type','c')
				->where('c.slug = :type')
				->setParameter('type', $value);
  		}
  		else{
  			$qb->select('c')
				->from('KSDealBundle:Type','c')
				->where('c.id = :type')
				->setParameter('type', $cast);
  		}

  		return $qb->getQuery()
				  ->getOneOrNullResult();

  	}
}