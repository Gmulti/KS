<?php

namespace KS\DealBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
	public function getDealsByCategory($category, $limit = 0, $offset = 10)
	{
    	$qb = $this->_em->createQueryBuilder();

		$qb->select('d')
			->from('KSDealBundle:Deal','d')
			->join('d.categories' , 'c')
			->addSelect('c')
			->where('c.id = :category')
			->setParameter('category', $category->getId())
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
				->from('KSDealBundle:Category','c')
				->where('c.slug = :category')
				->setParameter('category', $value);
  		}
  		else{
  			$qb->select('c')
				->from('KSDealBundle:Category','c')
				->where('c.id = :category')
				->setParameter('category', $cast);
  		}

  		return $qb->getQuery()
				  ->getOneOrNullResult();

  	}
}