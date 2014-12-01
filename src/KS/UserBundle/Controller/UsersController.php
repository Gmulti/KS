<?php

namespace KS\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\Controller\Annotations\NamePrefix;    
use FOS\RestBundle\View\RouteRedirectView;                
use FOS\RestBundle\View\View AS FOSView;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;         
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController as RestController;


/**
 *
 * @NamePrefix("api_v1_users_")
 */
class UsersController extends RestController
{


    /**
     * Retourne la liste des utilisateurs
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     */
	public function getUsersAction()
    {	
        $view = FOSView::create();

        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSUserBundle:User')
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
     * Retourne un utilisateur
     *
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     */
    public function getUserAction($username){

       
        $view = FOSView::create();
        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSUserBundle:User')
            ->findOneByUsername($username);

        if ($data) {
            $view->setStatusCode(200)->setData($data);
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);

    }

    /**
     *
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     */
    public function putUserAction($username)
    {

    } // "put_user"      [PUT] /users/{slug}

    /**
     *
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     */
    public function deleteUserAction($username)
    {
        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSUserBundle:User')
            ->findOneByUsername($username);

        $user = $this->get('security.context')->getToken()->getUser();

        if($data->getId() == $user->getId()){
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->remove($data);
            $dm->flush();

            $view = $this->view($data, 202);
            return $this->handleView($view);
        }

        $data = array('error', 'No user');
        $view = $this->view($data, 404);
        return $this->handleView($view);


    } // "delete_user"   [DELETE] /users/{slug}

    /**
     *
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     */
    public function getRoleAction($username){

        $view = FOSView::create();
        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSUserBundle:User')
            ->findOneByUsername($username);

        if ($data) {
            $view->setStatusCode(200)->setData($data->getRoles());
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     *
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     * @Get("/username/{token}")
     *
     */
    public function getUsernameByTokenAction($token){
        $view = FOSView::create();
        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByToken($token);

        if ($data) {
            $view->setStatusCode(200)->setData($data->getUserId());
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     *
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     * @Get("/role/{token}")
     *
     */
    public function getRoleByTokenAction($token){
        $view = FOSView::create();
        
        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByToken($token);

        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByToken($data->getUserId());


        if ($data) {
            $view->setStatusCode(200)->setData($data->getRoles());
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }
}
