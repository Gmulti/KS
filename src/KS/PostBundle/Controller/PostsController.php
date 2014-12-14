<?php

namespace KS\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request; 
use JMS\SecurityExtraBundle\Annotation\Secure;       

use FOS\RestBundle\Controller\Annotations\NamePrefix;    
use FOS\RestBundle\View\RouteRedirectView;                
use FOS\RestBundle\View\View AS FOSView;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RequestParam; 
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post as MethodPost;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController as RestController;

use KS\PostBundle\Document\Post;
use KS\PostBundle\Form\Type\PostType;
use KS\MediaBundle\Document\Media;


/**
 *
 * @NamePrefix("api_v1_posts_")
 */
class PostsController extends RestController
{
	
    /**
     * Return a posts list
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     */
	public function getPostsAction()
    {	
        $view = FOSView::create();

        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSPostBundle:Post')
            ->findAll();

        if ($data) {
            $view = $this->view($data, 200);
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     * Return a post
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     * @ParamConverter("post")
     *
     */
    public function getPostAction(Post $post){

        $view = FOSView::create();

        if ($post) {
            $view = $this->view($post, 200);
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

  
    /**
     * Edit a post
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     */
    public function postPostAction(Request $request){

        $view = FOSView::create();

        $newPost = $this->container->get('kspost.handler.user')->post(
            $request
        );

        if(null !== $newPost){
             $view = $this->view($newPost, 200);

        }
        else{
            $view->setStatusCode(404,array('error' => '404'));
        }

        return $this->handleView($view);      

    }

}
