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

use KS\DealBundle\Entity\Comment;
use KS\DealBundle\Entity\Deal;


/**
 *
 * @NamePrefix("api_v1_comments_")
 */
class CommentsController extends RestController
{
    
    /**
     * Return comments list
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset deals")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Limit deals")
     *
     */
    public function getCommentsAction(ParamFetcher $params){      

    }

    /**
     * Return a comment
     *
     * @ParamConverter("Deal")
     * @ParamConverter("Comment")
     */
    public function getCommentAction(Deal $deal, Comment $comment){

    }

  
    /**
     * Create a comment
     * @ParamConverter("Deal")
     */
    public function postCommentAction(Request $request, Deal $deal){


    }

    /**
     * Edit a comment
     * @ParamConverter("Deal")
     * @ParamConverter("Comment")
     *
     */
    public function putCommentAction(Request $request, Deal $deal, Comment $comment){

    }

    /**
     * Delete a Comment
     * @ParamConverter("Comment")
     *
     */
    public function deleteCommentAction(Deal $deal, Comment $comment){
    
 
    }

}
