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
use FOS\RestBundle\Controller\FOSRestController;

use KS\UserBundle\Entity\User;

/**
 *
 * @NamePrefix("api_v1_users_follow_")
 */
class UsersFollowController extends FOSRestController
{

    /**
     * Follow a user
     * @ParamConverter("userFollowed", options={"repository_method": "findByIdOrUsername" })
     *
     */
    public function postUserFollowAction(User $userFollowed, Request $request){
        $view = FOSView::create();

        $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $userRequest = $this->getDoctrine()->getManager()
                     ->getRepository('KSUserBundle:User')->findOneByUsername($username);

        if($userRequest->getUsername() != $userFollowed->getUsername()){
            $userFollow = $this->container->get('ksuser.handler.userfollow')->post(
                $userFollowed, $userRequest
            );
            if(null !== $userFollow){
             $view = $this->view($userFollow, 200);

            }
            else{
                $error = array(
                  'error' => 'already_follow_user', 
                  'error_description' => $this->get('translator')->trans('already_follow_user')
                );
                $view = $this->view($error, 404);
            }
        }
         else{
                $error = array(
                  'error' => 'not_auto_follow', 
                  'error_description' => $this->get('translator')->trans('not_auto_follow')
                );
                $view = $this->view($error, 404);
            }
       
        
      

        return $this->handleView($view);  
    }
    


    /**
     * Unfollow a user
     * @ParamConverter("userFollowed", options={"repository_method": "findByIdOrUsername" })
     *
     */
    public function postUserUnfollowAction(User $userFollowed, Request $request){
        $view = FOSView::create();

        $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $userRequest = $this->getDoctrine()->getManager()
                     ->getRepository('KSUserBundle:User')->findOneByUsername($username);

        if($userRequest->getId() !== $userFollowed->getId()){
            $userFollow = $this->container->get('ksuser.handler.userfollow')->delete(
                $userFollowed, $userRequest
            );
            
            if(null !== $userFollow){
                 $view = $this->view($userFollow, 200);

            }
            else{
                $error = array(
                  'error' => 'no_follow', 
                  'error_description' => $this->get('translator')->trans('no_follow_user')
                );
                $view = $this->view($error, 404);
            }
        }
        else{
            $error = array(
              'error' => 'not_auto_follow', 
              'error_description' => $this->get('translator')->trans('not_auto_follow')
            );
            $view = $this->view($error, 404);
        }

        

        return $this->handleView($view);  
    }    
}
