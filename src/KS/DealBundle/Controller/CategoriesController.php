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

use KS\DealBundle\Entity\Category;


/**
 *
 * @NamePrefix("api_v1_categories_")
 */
class CategoriesController extends RestController
{
    
    /**
     * Return Categories list
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset categories")
     * @QueryParam(name="limit", requirements="\d+", default="100", description="Limit categories")
     * @QueryParam(name="compact", requirements="\d+", default="0", description="Compact categories")
     *
     */
    public function getCategoriesAction(ParamFetcher $params){      
        $view = FOSView::create();
        
        $data = $this->getCategoriesWithParams($params);

        if ($data) {
            $view = $this->view($data, 200);
            $view->setData($data);
        }
        else{

            $errors = array(
                'error' => 'not_found',
                'error_description' => 'No categories have found',
            );
            $view->setStatusCode(404, $errors);
        }

        return $this->handleView($view);
    }

    /**
     * Return a Category
     *
     * @ParamConverter("category", options={"repository_method": "findByIdOrSlug" })
     */
    public function getCategoryAction(Category $category){

        $view = FOSView::create();

        if ($category) {
            $view = $this->view($category, 200);
        }
        else{
            $error = array(
                'error' => 'not_found',
                'error_description' => 'Category not found'
            );
            $view->setStatusCode(404, $error);
        }

        return $this->handleView($view);
    }

    private function getCategoriesWithParams($params){
        $offset = $params->get('offset');
        $limit = $params->get('limit');
        $compact = $params->get('compact');

        if ($compact) {
            $data = $this->getDoctrine()->getManager()
                         ->getRepository('KSDealBundle:Category')->getAllParent();

            $data = $this->getDoctrine()->getManager()
                        ->getRepository('KSDealBundle:Category')->buildTreeArrayCategory($data);

        }
        else{
            $data = $this->getDoctrine()->getManager()
                        ->getRepository('KSDealBundle:Category')
                        ->findBy(array(), array(), $limit, $offset);
        }
        

        return $data;
    }

}
