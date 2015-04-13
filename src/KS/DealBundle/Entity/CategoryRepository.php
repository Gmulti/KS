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

  	public function getAllParent(){
  		$qb = $this->_em->createQueryBuilder();
  		$qb->select('c')
		    ->from('KSDealBundle:Category', 'c')
		    ->orderBy('c.root, c.lft', 'ASC');

		return $qb->getQuery()->getArrayResult();
  	}

  	public function buildTreeArrayCategory($nodes){
	 
        $nestedTree = $this->buildTreeArray($nodes);

        $build = function ($tree) use (&$build) {
            foreach ($tree as $node) {

                $output[$node['id']]['id'] = $node['id']; 
                $output[$node['id']]['slug'] = $node['slug']; 
                $output[$node['id']]['title'] = $node['title']; 
                if (count($node['__children']) > 0) {
                    $output[$node['id']]['children'] = $build($node['__children']);
                }
            }

            return $output;
        };

        $data = $build($nestedTree);
        $this->sortTree($data);
        return $data;
	}

  private function sortTree( &$array )
    {
        if (!is_array($array)) {
            return false;
        }

        sort($array);
        foreach ($array as $k=>$v) {
            $this->sortTree($array[$k]['children']);
        }
        return true;
    }

}