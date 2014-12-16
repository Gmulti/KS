<?php

namespace KS\PostBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use KS\PostBundle\Form\Type\PostType;
use KS\PostBundle\Document\Post;

class PostHandler implements PostHandlerInterface{

	protected $formFactory;

	public function __construct(ManagerRegistry $om, $entityClass, FormFactoryInterface $formFactory)
	{
	    $this->om = $om;
	    $this->entityClass = $entityClass;
	    $this->formFactory = $formFactory;
	    $this->repository = $this->om->getRepository($this->entityClass);
	}

	private function getErrorMessages(\Symfony\Component\Form\Form $form) {      
	    $errors = array();

	        foreach ($form->getErrors() as $key => $error) {
	            $errors[] = $error->getMessage();
	        }   
	   

	    return $errors;
	}

	private function processForm(Post $post, Request $request, $method = "PUT"){



		$form = $this->formFactory->create(new PostType(), $post, array('method' => $method));

	    $form->handleRequest($request);

	    var_dump($this->getErrorMessages($form));
	    var_dump($form->getErrorsAsString());
	    if ($form->isValid()) {
	    	var_dump('valid');
	    	die();
	        $this->om->getManager()->persist($post);
	        $this->om->getManager()->flush($post);

	        return $post;
	    }

	    var_dump('fezfez');
	    return null;
	}

    public function put(Request $request, Post $post){

    	return $this->processForm($post, $request);
    }

    public function post(Request $request){
    	$post = new Post();

	    return $this->processForm($post, $request, 'POST');
    }



}