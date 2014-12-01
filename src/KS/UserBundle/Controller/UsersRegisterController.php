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

use KS\UserBundle\Document\User;

/**
 *
 * @NamePrefix("api_v1_users_")
 */
class UsersRegisterController extends FOSRestController
{


    /**
     * Creates a new User entity.
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", requirements="^\w+", default="", description="Username")
     * @RequestParam(name="email", requirements="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$", default="", description="Email")
     * @RequestParam(name="password", requirements="\w+", default="", description="Mot de passe")
     *
     * @Post("/users/register")
     *
     * @return FOSView
     */
    public function registerUsersAction(ParamFetcher $params)
    {
    	$view = FOSView::create();

        $user = new User();
        $user->setUsername($params->get('username'));
        $user->setEmail($params->get('email'));
        $user->setPlainPassword($params->get('password'));
        $user->setRoles(array('ROLE_USER'));
        $user->setScopes(array('public'));

        $validator = $this->get('validator');

        $errors = $validator->validate($user, array('Registration'));

        if (count($errors) == 0) {
        	// Extend fos user manager
        	$userManager = $this->container->get('fos_user.user_manager');
            $userManager->updateUser($user);
            $view->setStatusCode(200)->setData($user);
        } 

        return $this->handleView($view);

    }

  

}
