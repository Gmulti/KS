<?php

namespace KS\UserBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;


use Doctrine\ORM\EntityManager;
use KS\UserBundle\Form\Type\UserType;
use KS\UserBundle\Entity\User;
use FOS\RestBundle\Request\ParamFetcher;

use KS\DealBundle\Exception\InvalidFormException;

class UserHandler implements UserHandlerInterface{

	protected $formFactory;

	public function __construct(EntityManager $em, $entityClass, FormFactoryInterface $formFactory)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->formFactory = $formFactory;
	    $this->repository = $this->em->getRepository($this->entityClass);
	    $this->putConfig  = array('lastname','firstname','birthday');
	}


	private function processForm(User $user, Request $request, $method = "PUT"){
		
		$form = $this->createForm($user, $request, $method);
	    $form->handleRequest($request);

	    if ($form->isValid()) {

	 		if ($method == "PUT") {
		 		
		        $this->em->persist($user);
	 		}

	        $this->em->flush();

	        return $user;
	    }

		throw new InvalidFormException('Invalid submitted data', $form);
	}

	private function createForm(User $user, Request $request, $method){
		$config = array();

		if($method === "PUT"){
			foreach ($request->request as $key => $value) {
				if(in_array($key, $this->putConfig)){
					$config[$key] = array(
						'category' => $this->getCategoryField($key),
						'options' => $this->getOptionsField($key),
					);
				}
			}
		}

		return $this->formFactory->create(new UserType($config), $user, array('method' => $method));

	}

	private function getCategoryField($field){

		switch ($field) {
			default:
				$result = null;
				break;
		}

		return $result;
	}

	private function getOptionsField($field){

		switch ($field) {
			default:
				$result = array();
				break;
		}

		return $result;
	}


    public function put(User $user, Request $request){

    	return $this->processForm($user, $request);
    }

    public function delete(User $user){

    	try {
    		$this->em->remove($user);
    		$this->em->flush();

    	} catch (Exception $e) {
    		return array(
    			'error' => 'exception',
    			'error_description' => 'Delete error'
    		);
    	}

    	return array(
    		'success' => 'delete_success',
    		'success_description' => 'Delete user with success'

    	);
    }
}
