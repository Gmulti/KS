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

use KS\UserBundle\Document\User;

/**
 *
 * @NamePrefix("api_v1_users_")
 */
class UsersController extends RestController
{


    /**
     * Return users list
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
     * Return a user
     *
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     * @ParamConverter("user")
     */
    public function getUserAction(User $user){

       
        $view = FOSView::create();

        if ($user) {
            $view = $this->view($user, 200);
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
     * Edit a user
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("user")
     *
     */
    public function putUserAction(Request $request, User $user){
    
        $view = FOSView::create();


        if($this->container->get('ksuser.utils.usertoken')->isAccessToRequest($request, $user)){
            $updateUser = $this->container->get('ksuser.handler.user')->put(
                $user, $request 
            );

            if(null !== $updateUser){
                $view = $this->view($updateUser, 200);

            }
            else{
                $view->setStatusCode(404,array(
                    'error' => 'error_put', 
                    'error_description' => 'Erreur sur le traitement des données'
                    )
                );
            }
        }
        else{
            $view->setStatusCode(401,array(
                'error' => 'no_access', 
                'error_description' => 'Unauthorized update user'
                )
            );
        }

        return $this->handleView($view);   
    }

    /**
     * @Route(requirements={"_format"="json|xml"})
     * @ParamConverter("user")
     */
    public function deleteUserAction(User $user)
    {
        $view = FOSView::create();

        $deleteUser = $this->container->get('ksuser.handler.user')->delete(
            $user 
        );
        
        if(null !== $deleteUser){
             $view = $this->view($deleteUser, 202);

        }
        else{
            $view->setStatusCode(404,$deleteUser);
        }

        return $this->handleView($view);   


    } // "delete_user"   [DELETE] /users/{slug}

    /**
     * Get user's roles
     *
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
     * Get username from token
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     * @Get("/username")
     *
     */
    public function getUsernameByTokenAction(Request $request){
        $view = FOSView::create();
      
        $data = $this->container->get('ksuser.utils.usertoken')->getAccessTokenByTokenRequest($request);

        if ($data) {
            $view->setStatusCode(200)->setData($data->getUsername());
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     * Get roles from token
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

        $user = $this->get('doctrine_mongodb')
            ->getRepository('KSUserBundle:User')
            ->findOneByToken($data->getUserId());


        if ($user) {
            $view->setStatusCode(200)->setData($user->getRoles());
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     * Delete token / logout
     *
     * @Secure(roles="ROLE_USER")
     * @Post("/logout")
     *
     */
    public function logoutAction(){
        $view = FOSView::create();
        
        $token = $this->container->get('ksuser.utils.usertoken')->getAccessTokenFromRequest($this->getRequest());

        $data = $this->get('doctrine_mongodb')
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByToken($token);


        if ($data) {

            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->remove($data);
            $dm->flush();

            $view->setStatusCode(200);
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    
}
