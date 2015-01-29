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
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset posts")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Limit posts")
     *
     */
    public function getPostsAction(ParamFetcher $params)
    {      
        $view = FOSView::create();
        
        $data = $this->getPostsWithParams($params);

        if ($data) {
            $view = $this->view($data, 200);
            $view->setData($data);
        }
        else{

            $errors = array(
                'error' => 'not_found',
                'error_description' => 'No users have found',
            );
            $view->setStatusCode(404, $errors);
        }

        return $this->handleView($view);
    }

    /**
     * Return a post
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     *
     * @ParamConverter("post")
     */
    public function getPostAction(Post $post){

        $view = FOSView::create();

        if ($post) {
            $view = $this->view($post, 200);
        }
        else{
            $error = array(
                'error' => 'not_found',
                'error_description' => 'User not found'
            );
            $view->setStatusCode(404, $error);
        }

        return $this->handleView($view);
    }

  
    /**
     * Create a post
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     */
    public function postPostAction(Request $request){

        $view = FOSView::create();

        $newPost = $this->container->get('kspost.handler.post')->post(
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



    /**
     * Edit a post
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("post")
     *
     */
    public function putPostAction(Request $request, Post $post){
    
        $view = FOSView::create();

        $updatePost = $this->container->get('kspost.handler.post')->put(
            $post, $request 
        );

        if(null !== $updatePost){
            $view = $this->view($updatePost, 200);

        }
        else{
            $view->setStatusCode(404,array('error' => '404'));
        }

        return $this->handleView($view);   
    }

    /**
     * Edit a post
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("post")
     *
     */
    public function deletePostAction(Post $post){
    
        $view = FOSView::create();

        $deletePost = $this->container->get('kspost.handler.post')->delete(
            $post 
        );

        if(null !== $deletePost){
             $view = $this->view($deletePost, 200);

        }
        else{
            $view->setStatusCode(404,array('error' => '404'));
        }

        return $this->handleView($view);   
    }


    private function getPostsWithParams(ParamFetcher $params){

        $offset = $params->get('offset');
        $limit = $params->get('limit');

        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSPostBundle:Post')
            ->findBy(array(), array('updated' => 'DESC'), $limit, $offset);

        return $data;
    }

}
