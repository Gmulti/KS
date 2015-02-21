<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\EntityRepository;

class DealRepository extends EntityRepository
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

  
}