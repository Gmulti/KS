<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;
use KS\DealBundle\Form\Type\CommentType;
use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Entity\Comment;
use FOS\RestBundle\Request\ParamFetcher;

use KS\DealBundle\Exception\InvalidFormException;

class CommentHandler implements CommentHandlerInterface{

	protected $formFactory;

	protected $configFiles = true;

	public function __construct(EntityManager $em, $entityClass, FormFactoryInterface $formFactory, $categoryOptionField)
	{
	    $this->em = $em;
	    $this->entityClass = $entityClass;
	    $this->formFactory = $formFactory;
	    $this->categoryOptionField = $categoryOptionField;
	    $this->repository = $this->em->getRepository($this->entityClass);
	    $this->postConfig = array('user','deal','content');
	    $this->putConfig  = array('content');
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
	private function processForm(Comment $comment, Request $request, $method = "PUT"){
		
		$form = $this->createForm($comment, $request, $method);
	    $form->handleRequest($request);

	    if ($form->isValid()) {

	 		if ($method !== "PUT") {

		        $this->em->persist($comment);

		        if($this->configFiles){
		        	$medias = $comment->getMedias();
		           	foreach ($medias as $key => $media) {
		        		$media->setComment($comment);
		        		$media->setUser($comment->getUser());
		        		$this->em->persist($media);
		        	}
		        }
	 		}

	        $this->em->flush();

	        return $comment;
	    }

		throw new InvalidFormException('Invalid submitted data', $form);
	}

	private function createForm(Comment $comment, Request $request, $method){
		
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
		}


		$form = $this->formFactory->create(new CommentType($config), $comment, array('method' => $method));

		return $form;
	}


    public function put(Comment $comment, Request $request){

    	return $this->processForm($comment, $request);
    }

    public function post(Deal $deal, Request $request){
    	$comment = new Comment();
    	$comment->setDeal($deal);

	    return $this->processForm($comment, $request, 'POST');
    }

    public function delete(Comment $comment){

    	try {

    		$this->em->remove($comment);
    		$this->em->flush();

    	} catch (Exception $e) {
    		return array(
    			'error' => 'exception_delete',
    			'error_description' => 'Comment delete error'
    		);
    	}

    	return array(
    		'success' => 'delete_success',
    		'success_description' => 'Delete comment with success'
    	);
    }
}
