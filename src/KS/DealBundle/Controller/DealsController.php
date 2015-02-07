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
 * @NamePrefix("api_v1_deals_")
 */
class DealsController extends RestController
{
    
    /**
     * Return a deals list
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset deals")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Limit deals")
     *
     */
    public function getDealsAction(ParamFetcher $params)
    {      
        $view = FOSView::create();
        
        $data = $this->getDealsWithParams($params);

        if ($data) {
            $view = $this->view($data, 200);
            $view->setData($data);
        }
        else{

            $errors = array(
                'error' => 'not_found',
                'error_description' => 'No users have found',
            );
            $view->setStatusCode(404, $errors);
        }

        return $this->handleView($view);
    }

    /**
     * Return a Deal
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @Route(requirements={"_format"="json|xml"})
     *
     * @ParamConverter("Deal")
     */
    public function getDealAction(Deal $deal){

        $view = FOSView::create();

        if ($deal) {
            $view = $this->view($deal, 200);
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
     * Create a Deal
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     */
    public function postDealAction(Request $request){

        $view = FOSView::create();
        $user = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $request->request->set('user',$user);
        
        $newDeal = $this->container->get('ksdeal.handler.deal')->post(
            $request
        );

        if(null !== $newDeal){
             $view = $this->view($newDeal, 200);

        }
        else{
            $view->setStatusCode(404,array(
                'error' => 'error_Deal', 
                'error_description' => 'Erreur sur le traitement des données'
                )
            );
        }

        return $this->handleView($view);      

    }



    /**
     * Edit a Deal
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("Deal")
     *
     */
    public function putDealAction(Request $request, Deal $deal){
    
        $view = FOSView::create();

        $updatedeal = $this->container->get('ksdeal.handler.deal')->put(
            $deal, $request 
        );

        if(null !== $updateDeal){
            $view = $this->view($updateDeal, 200);

        }
        else{
            $view->setStatusCode(404,array(
                'error' => 'error_put', 
                'error_description' => 'Erreur sur le traitement des données'
                )
            );
        }

        return $this->handleView($view);   
    }

    /**
     * Edit a Deal
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("Deal")
     *
     */
    public function deleteDealAction(Deal $deal){
    
        $view = FOSView::create();

        $deletedeal = $this->container->get('ksdeal.handler.deal')->delete(
            $deal 
        );
        
        if(null !== $deleteDeal){
             $view = $this->view($deleteDeal, 202);

        }
        else{
            $view->setStatusCode(404,$deleteDeal);
        }

        return $this->handleView($view);   
    }


    private function getDealsWithParams(ParamFetcher $params){

        $offset = $params->get('offset');
        $limit = $params->get('limit');

        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSDealBundle:Deal')
            ->findBy(array(), array('updated' => 'DESC'), $limit, $offset);

        return $data;
    }

}
