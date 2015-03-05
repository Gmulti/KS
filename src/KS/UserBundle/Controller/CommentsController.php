<?php

namespace KS\UserBundle\Controller;

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
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController as RestController;

use KS\DealBundle\Entity\Comment;
use KS\UserBundle\Entity\User;


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
     * @ParamConverter("user", options={"repository_method": "findByIdOrUsername" })
     *
     */
    public function getUserCommentsAction(User $user, ParamFetcher $params){      
       $view = FOSView::create();
        
        $data = $this->getCommentsWithParams($user, $params);

        if ($data) {
            $view = $this->view($data, 200);
            $view->setData($data);
        }
        else{

            $errors = array(
                'error' => 'not_found',
                'error_description' => 'No comments have found',
            );
            $view = $this->view($errors, 404);
        }

        return $this->handleView($view);
    }

    /**
     * Return a comment
     *
     * @ParamConverter("user", options={"repository_method": "findByIdOrUsername" })
     */
    public function getUserCommentAction(User $user, $idComment){

        $view = FOSView::create();

        $comment = $this->getCommentByDealAndId($user, $idComment);

        if ($comment) {
            $view = $this->view($comment, 200);
        }
        else{
            $error = array(
                'error' => 'not_found',
                'error_description' => 'Comment not found'
            );
            $view = $this->view($error, 404);
        }

        return $this->handleView($view);
    }


    private function getCommentsWithParams($user, $params){

        $offset = $params->get('offset');
        $limit = $params->get('limit');
     

        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSDealBundle:Comment')
            ->findBy(
                array('user' => $user),
                array('updated' => 'DESC'),
                $limit, 
                $offset
            );

        return $data;
    }

    private function getCommentByDealAndId(User $user, $idComment){

        return $this->getDoctrine()->getManager()
                    ->getRepository('KSDealBundle:Comment')
                    ->findOneBy(array(
                            'user' => $user,
                            'id'   => $idComment
                        )
                    ); 
    }

}
