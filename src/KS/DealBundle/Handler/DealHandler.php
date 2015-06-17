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

	public function __construct(EntityManager $em, $entityClass, FormFactoryInterface $formFactory, $categoryOptionField)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->formFactory = $formFactory;
	    $this->categoryOptionField = $categoryOptionField;
	    $this->repository = $this->em->getRepository($this->entityClass);
	    $this->postConfig = array(
	    	'user',
	    	'content',
	    	'medias',
	    	'price',
	    	'url',
	    	'categories',
	    	'lat',
	    	'lng',
	    	'address',
	    	'type',
	    	'promoCode',
	    	'reduction',
	    	'reductionType',
	    	'title',
	    	'expiration',
	    	'currency'
	    );
	    $this->putConfig  = array(
	    	'content',
	    	'medias',
	    	'price',
	    	'url',
	    	'lat',
	    	'lng',
	    	'address',
	    	'categories',
	    	'type',
	    	'promoCode',
	    	'reduction',
	    	'reductionType',
	    	'title',
	    	'expiration',
	    	'currency'
	    );
	}

	private function processForm(Deal $deal, Request $request, $method = "PUT"){

		$form = $this->createForm($deal, $request, $method);
	    $form->handleRequest($request);

	    if ($form->isValid()) {

	 		if ($method !== "PUT") {

		        $this->em->persist($deal);

		        if($this->configFiles){
		        	$medias = $deal->getMedias();
		        	$i = 0;
		           	foreach ($medias as $key => $media) {
		           		if($i > 3){
		           			break;
		           		}
		        		$media->setDeal($deal);
		        		$media->setUser($deal->getUser());
		        		$this->em->persist($media);
		        		$i++;
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
						'category' =>$this->categoryOptionField->getCategoryField($key),
						'options' =>$this->categoryOptionField->getOptionsField($key),
					);
				}
			}
		}
		else{
			foreach ($request->request as $key => $value) {

				if(in_array($key, $this->postConfig)){
					$config[$key] = array(
						'category' => $this->categoryOptionField->getCategoryField($key),
						'options' => $this->categoryOptionField->getOptionsField($key),
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
					'category' => $this->categoryOptionField->getCategoryField('medias'),
					'options' => $this->categoryOptionField->getOptionsField('medias'),
				);
			}


			
			$type = $request->request->get('type');
			if ($type !== null) {
				switch ($type) {
					case 'code-promo':

						if (!array_key_exists('promoCode', $config)) {
							$config['promoCode'] = array(
								'category' => $this->categoryOptionField->getCategoryField('promoCode'),
								'options' => $this->categoryOptionField->getOptionsField('promoCode'),
							);
						}

						foreach (array('reductionType', 'reduction') as $key => $value) {
							if(array_key_exists($value, $config)){
								$config[$value] = null;
								$request->request->set($value, "");
							}
						}
						

						break;
					case 'reduction':
						if (!array_key_exists('reductionType', $config)) {
							$config['reductionType'] = array(
								'category' => $this->categoryOptionField->getCategoryField('reductionType'),
								'options' => $this->categoryOptionField->getOptionsField('reductionType'),
							);
						}
						if (!array_key_exists('reduction', $config)) {
							$config['reduction'] = array(
								'category' => $this->categoryOptionField->getCategoryField('reduction'),
								'options' => $this->categoryOptionField->getOptionsField('reduction'),
							);
						}

						if(array_key_exists('promoCode', $config)){
							$config['promoCode'] = null;
							$request->request->set("promoCode", "");
						}
						break;
					case 'bon-plan':
						foreach (array('reductionType', 'reduction', 'promoCode') as $key => $value) {
							if(array_key_exists($value, $config)){
								$config[$value] = null;
								$request->request->set($value, "");
							}
						}
						break;
				}
			}

			$price = $request->request->get('price');
			if($price !== null && $price > 0){
				if (!array_key_exists('currency', $config)) {
					$config['currency'] = array(
						'category' => $this->categoryOptionField->getCategoryField('currency'),
						'options' => $this->categoryOptionField->getOptionsField('currency'),
					);
				}
			}
			else{
				if (array_key_exists('currency', $config)) {
					$config["currency"] = null;
					$request->request->set("currency","");
				}
			}
		}


		$form = $this->formFactory->create(new DealType($config), $deal, array('method' => $method));

		return $form;
	}

	private function getErrorMessages(\Symfony\Component\Form\Form $form) {
	    $errors = array();

	    foreach ($form->getErrors() as $key => $error) {
	        if ($form->isRoot()) {
	            $errors['#'][] = $error->getMessage();
	        } else {
	            $errors[] = $error->getMessage();
	        }
	    }

	    foreach ($form->all() as $child) {
	        if (!$child->isValid()) {
	            $errors[$child->getName()] = $this->getErrorMessages($child);
	        }
	    }

	    return $errors;
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
