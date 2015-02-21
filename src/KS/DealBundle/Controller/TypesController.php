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

use KS\DealBundle\Entity\Type;


/**
 *
 * @NamePrefix("api_v1_types_")
 */
class TypesController extends RestController
{
    
    /**
     * Return Categories list
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset categories")
     * @QueryParam(name="limit", requirements="\d+", default="100", description="Limit categories")
     *
     */
    public function getTypesAction(ParamFetcher $params){      
        $view = FOSView::create();
        
        $data = $this->getTypesWithParams($params);

        if ($data) {
            $view = $this->view($data, 200);
            $view->setData($data);
        }
        else{

            $errors = array(
                'error' => 'not_found',
                'error_description' => 'No types have found',
            );
            $view->setStatusCode(404, $errors);
        }

        return $this->handleView($view);
    }

    /**
     * Return a Category
     *
     * @ParamConverter("type", options={"repository_method": "findByIdOrSlug" })
     */
    public function getTypeAction(Type $type){

        $view = FOSView::create();

        if ($type) {
            $view = $this->view($type, 200);
        }
        else{
            $error = array(
                'error' => 'not_found',
                'error_description' => 'Type not found'
            );
            $view->setStatusCode(404, $error);
        }

        return $this->handleView($view);
    }

    private function getTypesWithParams($params){
        $offset = $params->get('offset');
        $limit = $params->get('limit');

        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSDealBundle:Type')
            ->findBy(array(), array(), $limit, $offset);

        return $data;
    }

}
