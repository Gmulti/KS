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
    public function getCommentsAction(Deal $deal, ParamFetcher $params){      
       $view = FOSView::create();
        
        $data = $this->getCommentsWithParams($deal, $params);

        if ($data) {
            $view = $this->view($data, 200);
            $view->setData($data);
        }
        else{

            $errors = array(
                'error' => 'not_found',
                'error_description' => 'No deals have found',
            );
            $view->setStatusCode(404, $errors);
        }

        return $this->handleView($view);
    }

    /**
     * Return a comment
     *
     * @ParamConverter("deal")
     */
    public function getCommentAction(Deal $deal, $idComment){

        $view = FOSView::create();

        $comment = $this->getCommentByDealAndId($deal, $idComment);

        if ($comment) {
            $view = $this->view($comment, 200);
        }
        else{
            $error = array(
                'error' => 'not_found',
                'error_description' => 'Deal not found'
            );
            $view->setStatusCode(404, $error);
        }

        return $this->handleView($view);
    }

  
    /**
     * Create a comment
     * @ParamConverter("deal")
     */
    public function postCommentAction(Deal $deal, Request $request){

        $view = FOSView::create();
        $user = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $request->request->set('user',$user);

        $newComment = $this->container->get('ksdeal.handler.comment')->post(
            $deal,
            $request
        );

        if(null !== $newComment){
             $view = $this->view($newComment, 200);

        }
        else{
            $view->setStatusCode(404,array(
                'error' => 'error_comment', 
                'error_description' => 'Error on form submit'
                )
            );
        }

        return $this->handleView($view);      


    }

    /**
     * Edit a comment
     * @ParamConverter("deal")
     *
     */
    public function putCommentAction(Deal $deal, $idComment, Request $request){

    }

    /**
     * Delete a Comment
     * @ParamConverter("deal")
     *
     */
    public function deleteCommentAction(Deal $deal, $idComment){
    
 
    }


    private function getCommentsWithParams($deal, $params){

        $offset = $params->get('offset');
        $limit = $params->get('limit');
     

        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSDealBundle:Comment')
            ->findBy(
                array('deal' => $deal),
                array('updated' => 'DESC'),
                $limit, 
                $offset
            );

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
