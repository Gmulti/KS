<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;
use KS\DealBundle\Form\Type\DealType;
use KS\DealBundle\Entity\Deal;
use FOS\RestBundle\Request\ParamFetcher;

use KS\DealBundle\Exception\InvalidFormException;

class DealHandler implements DealHandlerInterface{

	protected $formFactory;

	protected $configFiles = true;

	public function __construct(EntityManager $em, $entityClass, FormFactoryInterface $formFactory)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->formFactory = $formFactory;
	    $this->repository = $this->em->getRepository($this->entityClass);
	    $this->postConfig = array('user','content','medias','price','url','categories');
	    $this->putConfig  = array('content','media','price','url');
	}

	private function processForm(Deal $deal, Request $request, $method = "PUT"){
		
		$form = $this->createForm($deal, $request, $method);
	    $form->handleRequest($request);

	    if ($form->isValid()) {

	 		if ($method !== "PUT") {

		        $this->em->persist($deal);

		        if($this->configFiles){
		        	$medias = $deal->getMedias();
		           	foreach ($medias as $key => $media) {
		        		$media->setDeal($deal);
		        		$media->setUser($deal->getUser());
		        		$this->em->persist($media);
		        	}
		        }
	 		}

	        $this->em->flush();

	        return $deal;
	    }

		throw new InvalidFormException('Invalid submitted data', $form);
	}

	private function createForm(Category $category, Request $request, $method){

		$form = $this->formFactory->create(new CategoryType(), $category, array('method' => $method));

		return $form;
	}


    public function put(Category $category, Request $request){

    	return $this->processForm($category, $request);
    }

    public function post(Request $request){
    	$category = new Category();

	    return $this->processForm($category, $request, 'POST');
    }

    public function delete(Category $category){

    	try {

    		$this->em->remove($category);
    		$this->em->flush();

    	} catch (Exception $e) {
    		return array(
    			'error' => 'exception_delete',
    			'error_description' => 'Delete error'
    		);
    	}

    	return array(
    		'success' => 'delete_success',
    		'success_description' => 'Delete category with success'
    	);
    }
}
