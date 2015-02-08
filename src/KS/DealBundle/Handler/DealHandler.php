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

	public function __construct(EntityManager $em, $entityClass, FormFactoryInterface $formFactory)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->formFactory = $formFactory;
	    $this->repository = $this->em->getRepository($this->entityClass);
	    $this->postConfig = array('user','content','media','price','url');
	    $this->putConfig  = array('content','media','price','url');
	}

	private function processForm(Deal $deal, Request $request, $method = "PUT"){
		
		$form = $this->createForm($deal, $request, $method);
	    $form->handleRequest($request);

	    if ($form->isValid()) {

	 		if ($method !== "PUT") {

		        $this->em->persist($deal);
	 		}

	        $this->em->flush();

	        return $deal;
	    }

		throw new InvalidFormException('Invalid submitted data', $form);
	}

	private function createForm(Deal $deal, Request $request, $method){
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
		else{
			foreach ($request->request as $key => $value) {

				if(in_array($key, $this->postConfig)){
					$config[$key] = array(
						'category' => $this->getCategoryField($key),
						'options' => $this->getOptionsField($key),
					);
				}
			}
	
			if($request->files->get('media') instanceOf \Symfony\Component\HttpFoundation\File\UploadedFile ){
				$config['media'] = array(
					'category' => $this->getCategoryField('media'),
					'options' => $this->getOptionsField('media'),
				);
			}
		}

		$form = $this->formFactory->create(new DealType($config), $deal, array('method' => $method));

		return $form;
	}

	private function getCategoryField($field){

		switch ($field) {
			case 'user':
				$result = 'user_selector';
				break;
			case 'media':
				$result = 'media_selector';
				break;
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


    public function put(Deal $deal, Request $request){

    	return $this->processForm($deal, $request);
    }

    public function post(Request $request){
    	$deal = new Deal();

	    return $this->processForm($deal, $request, 'POST');
    }

    public function delete(Deal $deal){

    	try {
   //  		$user = $deal->getUser();
   //  		$user->removeDeal($deal);

   //  		$media = $deal->getMedia();
   //  		if(null !== $media){
   //  			$user->removeMedia($media);
   //  			$media->removeDeal();
   //  			$media->removeUser();
   //  		}
    		
			// $deal->removeMedia();
   //  		$deal->removeUser();

   //  		// Update user
   //  		$this->em->getManager()->persist($user);
   //  		$this->em->getManager()->flush();
    	
   //  		// Remove
   //  		if(null !== $media){
   //  			$this->em->getManager()->remove($media);
   //  		}
    		$this->em->remove($deal);
    		$this->em->flush();

    	} catch (Exception $e) {
    		return array(
    			'error' => 'exception_delete',
    			'error_description' => 'Delete error'
    		);
    	}

    	return array(
    		'success' => 'delete_success',
    		'success_description' => 'Delete deal with success'
    	);
    }
}
