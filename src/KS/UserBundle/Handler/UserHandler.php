<?php

namespace KS\UserBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use KS\UserBundle\Form\Type\UserType;
use KS\UserBundle\Document\User;
use FOS\RestBundle\Request\ParamFetcher;

use KS\PostBundle\Exception\InvalidFormException;

class UserHandler implements UserHandlerInterface{

	protected $formFactory;

	public function __construct(ManagerRegistry $om, $entityClass, FormFactoryInterface $formFactory)
	{
	    $this->om = $om;
	    $this->entityClass = $entityClass;
	    $this->formFactory = $formFactory;
	    $this->repository = $this->om->getRepository($this->entityClass);
	    $this->putConfig  = array('lastname','firstname','birthday');
	}

	private function processForm(User $user, Request $request, $method = "PUT"){
		
		$form = $this->createForm($user, $request, $method);
	    $form->handleRequest($request);

	    if ($form->isValid()) {

	 		if ($method == "PUT") {
		 		
		        $this->om->getManager()->persist($user);
	 		}

	        $this->om->getManager()->flush();

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
    		$this->om->getManager()->remove($user);
    		$this->om->getManager()->flush();

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
