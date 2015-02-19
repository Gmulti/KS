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

			$files = $request->files->get('medias');
			$this->configFiles = true;

			if (!empty($files)) {
				foreach ($files as $key => $file) {
					if(!$file instanceOf \Symfony\Component\HttpFoundation\File\UploadedFile ){
						$this->configFiles = false;
					}
				}
			}
			else{
				$this->configFiles = false;
			}

			if($this->configFiles){
				$config['medias'] = array(
					'category' => $this->getCategoryField('medias'),
					'options' => $this->getOptionsField('medias'),
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
			case 'medias':
				$result = 'media_selector';
				break;
			case 'categories':
				$result = 'category_selector';
				break;
			default:
				$result = null;
				break;
		}

		return $result;
	}

	private function getOptionsField($field){

		switch ($field) {
			case 'medias':
				$result = array(
					'multiple' => true,
					'attr' => array(
						'multiple' => 'multiple'
					)
				);
				break;
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
