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
use JMS\SecurityExtraBundle\Annotation\Secure;

use FOS\RestBundle\Controller\FOSRestController;

class UsersAdminController extends FOSRestController
{

    /**
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route(requirements={"_format"="json|xml"})
     */
    public function lockUserAction($slug)
    {
    	
    } // "lock_user"     [PATCH] /users/{slug}/lock

    /**
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route(requirements={"_format"="json|xml"})
     */
    public function banUserAction($slug)
    {} // "ban_user"      [PATCH] /users/{slug}/ban

    /**
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route(requirements={"_format"="json|xml"})
     */
    public function removeUserAction($slug)
    {} // "remove_user"   [GET] /users/{slug}/remove
}
