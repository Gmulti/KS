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
 * @NamePrefix("api_v1_comments_like_")
 */
class CommentsLikeController extends RestController
{
    
    /**
     * Like a Comment
     * @ParamConverter("deal")
     *
     */
    public function postLikeAction(Deal $deal, $idComment, Request $request){
        $view = FOSView::create();
        
        $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $user = $this->getDoctrine()->getManager()
                     ->getRepository('KSUserBundle:User')->findOneByUsername($username);

        $comment = $this->getCommentByDealAndId($deal, $idComment);


        if ($comment !== null) {
            $commentLike = $this->container->get('ksdeal.handler.commentlike')->post(
                $comment, $user
            );

            if(null !== $commentLike){
                 $view = $this->view($commentLike, 200);

            }
            else{
                $view->setStatusCode(404,array(
                        'error' => 'already_like', 
                        'error_description' => 'You already like comment'
                    )
                );
            }
        }
        else{
            $view->setStatusCode(404,array(
                    'error' => 'not_found', 
                    'error_description' => 'Comment not found'
                )
            );
        }

        return $this->handleView($view); 
    }

    /**
     * Dislike a Comment
     * @ParamConverter("deal")
     *
     */
    public function postDislikeAction(Deal $deal, $idComment, Request $request){
        $view = FOSView::create();
        
        $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $user = $this->getDoctrine()->getManager()
                     ->getRepository('KSUserBundle:User')->findOneByUsername($username);

        $comment = $this->getCommentByDealAndId($deal, $idComment);
       
        if ($comment !== null) {
          
            $commentLike = $this->container->get('ksdeal.handler.commentlike')->delete(
                $comment, $user
            );

            if(null !== $commentLike){
                 $view = $this->view($commentLike, 200);

            }
            else{
                $view->setStatusCode(404,array(
                        'error' => 'no_like', 
                        'error_description' => 'Do not like this comment'
                    )
                );
            }

        }
        else{
            $view->setStatusCode(404,array(
                    'error' => 'not_found', 
                    'error_description' => 'Comment not found'
                )
            );
        }

        return $this->handleView($view);   
    }

    /**
     * Get likes
     * @ParamConverter("deal")
     * @QueryParam(name="username_only", requirements="\d+", default="0", description="Username only return")
     */
    public function getLikesAction(Deal $deal, $idComment, Request $request, ParamFetcher $params){
        $view = FOSView::create();

        $comment = $this->getCommentByDealAndId($deal, $idComment);

        $commentLikes = $this->getLikes($comment, $params);

        if(null !== $commentLikes){
             $view = $this->view($commentLikes, 200);

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

    private function getLikes($comment, $params){

    	$options['username_only'] = $params->get('username_only');

        $data = $this->getDoctrine()->getManager()
	            ->getRepository('KSDealBundle:Comment')
	            ->getLikes($comment,$options);

        return $data;
    }

    private function getCommentByDealAndId(Deal $deal, $idComment){

        return $this->getDoctrine()->getManager()
                    ->getRepository('KSDealBundle:Comment')
                    ->findOneBy(array(
                            'deal' => $deal,
                            'id'   => $idComment
                        )
                    ); 
    }


}
