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
use KS\DealBundle\Entity\Deal;


/**
 *
 * @NamePrefix("api_v1_categories_deals_")
 */
class CategoriesDealsController extends RestController
{
    
    /**
     * Return Categories list
     *
     * @ParamConverter("category", options={"repository_method": "findByIdOrSlug" })
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset categories")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Limit categories")
     *
     */
    public function getDealsAction(Category $category, ParamFetcher $params){      
        
        $view = FOSView::create();
        
        if (null === $category) {
        	$errors = array(
        		'error' => 'not_found',
        		'error_description' => 'Category not found'
        	);
        }else{
        	$data = $this->getDealsFromCategoryWithParams($category, $params);

	        if ($data && !empty($data)) {
	            $view = $this->view($data, 200);
	            $view->setData($data);
	        }
	        else{

	            $errors = array(
	                'error' => 'not_found',
	                'error_description' => 'No deals have found',
	            );
	            $view = $this->view($errors,404);
	        }
        }

        

        return $this->handleView($view);
    }

    private function getDealsFromCategoryWithParams($category, $params){
        $offset = $params->get('offset');
        $limit = $params->get('limit');

        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSDealBundle:Category')
            ->getDealsByCategory(
            	$category,
            	$limit, 
            	$offset
            );

        return $data;
    }

}
