<?php

namespace KS\DealBundle\Controller;

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

use KS\DealBundle\Entity\Deal;
use KS\DealBundle\Form\Type\DealType;
use KS\MediaBundle\Entity\Media;


/**
 *
 * @NamePrefix("api_v1_deals_share_")
 */
class DealsShareController extends RestController
{
    
    /**
     * Share a deal
     * @ParamConverter("deal")
     *
     */
    public function postShareAction(Deal $deal, Request $request){
        $view = FOSView::create();

        $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $user = $this->getDoctrine()->getManager()
                     ->getRepository('KSUserBundle:User')->findOneByUsername($username);

        $shareDeal = $this->container->get('ksdeal.handler.sharedeal')->post(
            $deal, $user
        );
        
        if(null !== $shareDeal){
             $view = $this->view($shareDeal, 200);

        }
        else{
            $error = array(
                'error' => 'already_share', 
                'error_description' => $this->get('translator')->trans('already_share_deal')
            );
            $view = $this->view($error, 404);
        }

        return $this->handleView($view);  
    }
}
