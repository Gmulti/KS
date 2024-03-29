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
			case 'content':
			// case 'title':
				$value = strtoupper($value);
				$qb->andWhere(
					$qb->expr()->orX(
						$qb->expr()->like('upper(d.content)',':content'),
						$qb->expr()->like('upper(d.title)',':content')
					)
				);
				$qb->setParameter('content', "%{$value}%");
				break;
			case 'date_offset':
				$qb->andWhere(
					$qb->expr()->gt('d.created',':date')
				);
				$date = new \DateTime($value);
				$qb->setParameter('date', $date->format("Y-m-d H:i:s"));
				break;
			case 'date_offset_end':
				$qb->andWhere(
					$qb->expr()->lt('d.created',':date')
				);
				$date = new \DateTime($value);
				$qb->setParameter('date', $date->format("Y-m-d H:i:s"));
				break;
			case 'user_id':
				$qb->andWhere('d.user = :user_id');
				$qb->setParameter('user_id', $value);
				break;
			case 'user':
				$qb->setParameter('user', $value);
				break;
			case 'type':
				$qb->join('d.type', 't');
				$qb->andWhere('t.slug = :type');
				$qb->setParameter('type', $value);
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
				$sql .= "AND d.price >= :start_price ";
				break;
			
			case 'end_price':
				// Error. Start price can't be greater than end price
				if ($value <= $this->start_price) {
					return $qb;
				}

				$sql .= "AND d.price <= :end_price ";
				break;
			case 'content':
				$sql .= "AND (upper(d.content) LIKE :content OR upper(d.title) LIKE :content) ";
				break;
			case 'date_offset':
				$sql .= "AND d.created > :date_offset ";
				break;
			case 'date_offset_end':
				$sql .= "AND d.created < :date_offset ";
				break;
			case 'user_id':
				$sql .= "AND d.user_id = :user_id ";
				break;
			case 'type':
				$sql .= "AND d.type_id = t.id ";
				$sql .= "AND t.slug = :type ";
				break;
		}

		return $sql;
	}

	/*
	 * Get deals (no geolocalisation)
	 */
  	private function getDealsNoLocationWithOptions($options, $limit, $offset){
  		$qb = $this->_em->createQueryBuilder();
  		$qbShared = $this->_em->createQueryBuilder();

		if(array_key_exists("user", $options)){

			$qbShared->select('userGet.id')
					->from('KSUserBundle:User', 'userGet')
					->join('userGet.followers','followersGet')
			        ->where('followersGet.subscribedUser = :user');

			$qb->select('d')
				->from('KSDealBundle:Deal','d')
				->orderBy('d.created', 'DESC')
		        ->leftJoin('d.user', 'u')
		        ->leftJoin('u.followers', 'f')
		        ->leftJoin('d.usersShared','us')
		        ->where($qb->expr()->orX(
					$qb->expr()->eq('f.subscribedUser', ':user'),
					$qb->expr()->in('us.id', $qbShared->getDQL())
			    ))
		        ->setFirstResult($offset)
		        ->setMaxResults($limit);


		}
		else{
			$qb->select('d')
				->from('KSDealBundle:Deal','d')
				->orderBy('d.created', 'DESC')
		        ->setFirstResult($offset)
		        ->setMaxResults($limit);
		}
	
		foreach ($options as $key => $value) {
			$qb = $this->setParameter($qb, $value, $key);
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
        	 WHERE earth_box(ll_to_earth(:lat,:lng),:distance) @> ll_to_earth(d.lat, d.lng) ";

    	foreach ($options as $key => $value) {
    		if (!in_array($key, array('distance', 'lat', 'lng'))) {
				$sql = $this->setParameterLocalisation($sql, $value, $key);
    		}
		}

		$sql .= "AND d.deletedat IS NULL ";
		$sql .= "ORDER BY d.created DESC ";
		$sql .= "LIMIT :limit ";
      
        $query = $this->_em->createNativeQuery($sql ,$rsm);

        $query->setParameter('lat', $options['lat']);
        $query->setParameter('lng', $options['lng']);
        $query->setParameter('distance', $options['distance']);
        $query->setParameter('limit', $limit);
        
        foreach ($options as $key => $value) {
    		if (!in_array($key, array('distance', 'lat', 'lng'))) {
				switch ($key) {
					case 'content':
					case 'title':
						$value = strtoupper($value);
						$query->setParameter($key, "%{$value}%");
						break;

					case 'date_offset':
					case 'date_offset_end':
						$date = new \DateTime($value);
						$query->setParameter($key, $date->format("Y-m-d H:i:s"));
						break;
					default:
						$query->setParameter($key, $value);
						break;
				}
    		}
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