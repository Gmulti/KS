<?php

namespace KS\DealBundle\Entity;

use Doctrine\ORM\EntityRepository;
use KS\UserBundle\Entity\User;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Models\ManyRepositoryInterface;
use KS\DealBundle\Models\ManyEntityInterface;
use KS\DealBundle\Models\ManyTypeInterface;
use KS\DealBundle\Models\LikeDealManyType;
use KS\DealBundle\Models\ShareDealManyType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class DealRepository extends EntityRepository implements ManyRepositoryInterface
{

	private $start_price;
	
	private $end_price;

	/*
	 * Get deals
	 */
	public function getDealsWithOptions($options, $limit = 0, $offset = 10)
	{	
		if (array_key_exists('start_price', $options)) {
			$this->start_price = $options['start_price'];
		}
		if(array_key_exists('end_price', $options)){
			$this->end_price   = $options['end_price'];
		}

		if (array_key_exists('lat', $options) || array_key_exists('lng', $options)) {
			$result = $this->getDealsLocationWithOptions($options, $limit, $offset);
		}
		else{
			$result = $this->getDealsNoLocationWithOptions($options, $limit, $offset);
		}

		return $result->getResult();
  	}

  	/*
	 * Set parameter (no geolocalisation query)
	 */
	private function setParameter($qb, $value, $key){

		switch ($key) {
			case 'start_price':
				$qb->andWhere('d.price >= :start_price');
				$qb->setParameter('start_price', $value);
				break;
			
			case 'end_price':
				// Error. Start price can't be greater than end price
				if ($value <= $this->start_price) {
					return $qb;
				}

				$qb->andWhere('d.price <= :end_price');
				$qb->setParameter('end_price', $value);
				break;
		}

		return $qb;
	}

	/*
	 * Set parameter (geolocalisation query)
	 */
	private function setParameterLocalisation($sql, $value, $key){

		switch ($key) {
			case 'start_price':
				$sql .= "AND d.price >= ?";
				break;
			
			case 'end_price':
				// Error. Start price can't be greater than end price
				if ($value <= $this->start_price) {
					return $qb;
				}

				$sql .= "AND d.price <= ?";
				break;
		}

		return $sql;
	}

	/*
	 * Get deals (no geolocalisation)
	 */
  	private function getDealsNoLocationWithOptions($options, $limit, $offset){
  		$qb = $this->_em->createQueryBuilder();

		$qb->select('d')
			->from('KSDealBundle:Deal','d')
			->orderBy('d.updated', 'DESC')
			->setFirstResult($offset)
			->setMaxResults($limit);

		foreach ($options as $key => $value) {
			switch ($key) {
				case 'start_price':
				case 'end_price':
					$qb = $this->setParameter($qb, $value, $key);
					break;
				
			}
		}

		return $qb->getQuery();
  	}

  	/*
	 * Get deals (geolocalisation)
	 */
  	private function getDealsLocationWithOptions($options, $limit, $offset){

  		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata('KS\DealBundle\Entity\Deal', 'd');
		$sql = "SELECT * 
        	 FROM ks_deal d 
        	 WHERE earth_box(ll_to_earth(?,?),?) @> ll_to_earth(d.lat, d.lng) ";

       	$i = 4;
       	$order = array();
    	foreach ($options as $key => $value) {
    		if (!in_array($key, array('distance', 'lat', 'lng'))) {
    			$order[$i] = $value;
				switch ($key) {
					case 'start_price':
					case 'end_price':
						$sql = $this->setParameterLocalisation($sql, $value, $key);
						break;
				}
				$i++;
    		}
		}

      
        $query = $this->_em->createNativeQuery($sql ,$rsm);

        $query->setParameter(1, $options['lat']);
        $query->setParameter(2, $options['lng']);
        $query->setParameter(3, $options['distance']);
        foreach ($order as $key => $value) {
        	$query->setParameter($key, $value);	
        }

        return $query;

  	}

  	public function getManyByUser(ManyEntityInterface $entityMany, User $user, ManyTypeInterface $typeMany){

  		$qb = $this->_em->createQueryBuilder();

		$qb->select('d')
			->from('KSDealBundle:Deal','d');

		if ($typeMany instanceof LikeDealManyType) {
			$qb->join('d.usersLikes' , 'u');
		}
		elseif ($typeMany instanceof ShareDealManyType){
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