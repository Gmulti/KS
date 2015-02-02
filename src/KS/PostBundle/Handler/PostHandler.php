<?php

namespace KS\PostBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use KS\PostBundle\Form\Type\PostType;
use KS\PostBundle\Document\Post;
use FOS\RestBundle\Request\ParamFetcher;

use KS\PostBundle\Exception\InvalidFormException;

class PostHandler implements PostHandlerInterface{

	protected $formFactory;

	public function __construct(ManagerRegistry $om, $entityClass, FormFactoryInterface $formFactory)
	{
	    $this->om = $om;
	    $this->entityClass = $entityClass;
	    $this->formFactory = $formFactory;
	    $this->repository = $this->om->getRepository($this->entityClass);
	    $this->postConfig = array('user','content','media','price','url');
	    $this->putConfig  = array('content','media','price','url');
	}

	private function processForm(Post $post, Request $request, $method = "PUT"){
		
		$form = $this->createForm($post, $request, $method);
	    $form->handleRequest($request);

	    if ($form->isValid()) {

	 		if ($method !== "PUT") {

	 			// Add post on user
		 		$user = $post->getUser();
		 		$user->addPost($post);

		 		// Add media on user
		 		$media = $post->getMedia();
		 		if($media !== null):
		 			$user->addMedia($media);
		 		endif;
		 		
		        $this->om->getManager()->persist($user);
	 		}

	        $this->om->getManager()->flush();

	        return $post;
	    }

		throw new InvalidFormException('Invalid submitted data', $form);
	}

	private function createForm(Post $post, Request $request, $method){

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

    public function delete(Post $post){

    	try {
    		$user = $post->getUser();
    		$user->removePost($post);

    		$media = $post->getMedia();
    		if(null !== $media){
    			$user->removeMedia($media);
    			$media->removePost();
    			$media->removeUser();
    		}
    		
			$post->removeMedia();
    		$post->removeUser();

    		// Update user
    		$this->om->getManager()->persist($user);
    		$this->om->getManager()->flush();
    	
    		// Remove
    		if(null !== $media){
    			$this->om->getManager()->remove($media);
    		}
    		$this->om->getManager()->remove($post);
    		$this->om->getManager()->flush();

    	} catch (Exception $e) {
    		return array(
    			'error' => 'exception',
    			'error_description' => 'Delete error'
    		);
    	}

    	// return $post;
    }
}
