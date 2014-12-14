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

	private function processForm(Post $post, Request $request, $method = "PUT"){

		$form = $this->formFactory->create(new PostType(), $post, array('method' => $method));
	    $form->handleRequest($request, 'PATCH' !== $method);

	    if ($form->isValid()) {

	        $this->om->getManager()->persist($post);
	        $this->om->getManager()->flush($post);

	        return $post;
	    }

	    return null;
	}

    public function get($id){

    }

    public function post(Request $request){
    	$post = new Post();

	    return $this->processForm($post, $request, 'POST');
    }



}