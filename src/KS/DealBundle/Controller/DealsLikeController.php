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
 * @NamePrefix("api_v1_deals_like_")
 */
class DealsLikeController extends RestController
{
    
    /**
     * Like a Deal
     * @ParamConverter("deal")
     *
     */
    public function postLikeAction(Deal $deal, Request $request){
        $view = FOSView::create();
        
        $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $user = $this->getDoctrine()->getManager()
                     ->getRepository('KSUserBundle:User')->findOneByUsername($username);

        $dealLike = $this->container->get('ksdeal.handler.deallike')->post(
            $deal, $user
        );

        if(null !== $dealLike){
             $view = $this->view($dealLike, 200);

        }
        else{
            $view->setStatusCode(404,array(
                    'error' => 'already_like', 
                    'error_description' => 'You already like deal'
                )
            );
        }

        return $this->handleView($view);   
    }

    /**
     * Dislike a Deal
     * @ParamConverter("deal")
     *
     */
    public function postDislikeAction(Deal $deal, Request $request){
        $view = FOSView::create();
        
        $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $user = $this->getDoctrine()->getManager()
                     ->getRepository('KSUserBundle:User')->findOneByUsername($username);

        $dealDislike = $this->container->get('ksdeal.handler.deallike')->delete(
            $deal, $user
        );

        if(null !== $dealDislike){
             $view = $this->view($dealDislike, 200);

        }
        else{
            $view->setStatusCode(404,array(
                    'error' => 'no_like', 
                    'error_description' => 'Do not like this deal'
                )
            );
        }

        return $this->handleView($view);   
    }

    /**
     * Get likes
     * @ParamConverter("deal")
     * @QueryParam(name="username_only", requirements="\d+", default="0", description="Username only return")
     *
     */
    public function getLikesAction(Deal $deal, Request $request, ParamFetcher $params){
        $view = FOSView::create();
        
        $dealLikes = $this->getLikes($deal, $params);

        if(null !== $dealLikes){
             $view = $this->view($dealLikes, 200);

        }
        else{
            $view->setStatusCode(404,array(
                    'error' => 'no_like', 
                    'error_description' => 'No likes'
                )
            );
        }

        return $this->handleView($view);   
    }

    private function getLikes($deal, $params){

    	$options['username_only'] = $params->get('username_only');

        $data = $this->getDoctrine()->getManager()
	            ->getRepository('KSDealBundle:Deal')
	            ->getLikes($deal,$options);

        return $data;
    }


}
