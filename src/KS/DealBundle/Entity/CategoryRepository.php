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
			->setFirstResult($offset)
			->setMaxResults($limit);

		return $qb->getQuery()
				  ->getResult();
  	}
}