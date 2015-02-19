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
 * @NamePrefix("api_v1_categories_admin_")
 */
class CategoriesAdminController extends RestController
{

  
    /**
     * Create a Category
     * @ParamConverter("Category")
     */
    public function postCategoryAction(Request $request, Category $category){


    }

    /**
     * Edit a Category
     * @ParamConverter("Category")
     *
     */
    public function putCategoryAction(Request $request, Category $category){

    }

    /**
     * Delete a Category
     * @ParamConverter("Category")
     *
     */
    public function deleteCategoryAction(Category $category){
    
 
    }

}
