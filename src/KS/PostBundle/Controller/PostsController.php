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

use KS\PostBundle\Form\Type\RegisterPostType;
use KS\PostBundle\Document\Post;


/**
 *
 * @NamePrefix("api_v1_posts_")
 */
class PostsController extends RestController
{
	
    /**
     * Retourne la liste des posts
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
     * Post un post
     *
     * @RequestParam(name="content", description="Content")
     * @RequestParam(name="user", description="User")
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     */
    public function postPostAction(ParamFetcher $params){

        $view = FOSView::create();

        $user = $this->get('doctrine_mongodb')
            ->getRepository('KSUserBundle:User')
            ->findOneByUsername($params->get('user'));

        if(!$user){
            $view->setStatusCode(404);
        }
        else{
            $entity = new Post();
            $entity->setUser($user);
            $entity->setContent($params->get('content'));

            $validator = $this->get('validator');
            $errors = $validator->validate($entity);

            if (count($errors) == 0) {

                $dm = $this->get('doctrine_mongodb')->getManager();
                $dm->persist($entity);
                $user->addPost($entity);
                $dm->flush();

                
                $view = $this->view($entity, 200);
            }
            else{
                $view->setStatusCode(404);
            }
        }

        return $this->handleView($view);   
    }

}
