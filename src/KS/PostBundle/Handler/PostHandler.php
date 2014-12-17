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
		
		$form = $this->createForm($post, $request, $method);
	    $form->handleRequest($request);

	    if ($form->isValid()) {

	 		if ($method !== "PUT") {
		 		$user = $post->getUser();
		 		$user->addPost($post);

		        $this->om->getManager()->persist($post);
	 		}

	        $this->om->getManager()->flush();

	        return $post;
	    }


	    return null;
	}

	private function createForm(Post $post, Request $request, $method){

		if($method === "PUT"){
			foreach ($request->request as $key => $value) {
				$config[$key] = array(
					'category' => $this->getCategoryField($key),
					'options' => $this->getOptionsField($key),
				);
			}
		}
		else{
			$config =  array(
	            'user' => array(
	            	'category' => $this->getCategoryField('user'),
	            	'options' => $this->getOptionsField('user')
	            ),
	            'content' => array(
	            	'category' => $this->getCategoryField('content'),
	            	'options' => $this->getOptionsField('content')
	            ),
	            'media' => array(
	            	'category' => $this->getCategoryField('media'),
	            	'options' => $this->getOptionsField('media')
	            )
	        );
		}

		$form = $this->formFactory->create(new PostType($config), $post, array('method' => $method));

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


    public function put(Post $post, Request $request){

    	return $this->processForm($post, $request);
    }

    public function post(Request $request){
    	$post = new Post();

	    return $this->processForm($post, $request, 'POST');
    }



}