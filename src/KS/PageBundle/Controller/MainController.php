<?php

namespace KS\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use KS\ServerBundle\Document\Client;
use KS\PostBundle\Form\Type\PostType;
use KS\PostBundle\Document\Post;

class MainController extends Controller
{
    /**
     * @Route("/", name="komunity_store_index")
     * @Template()
     */
    public function indexAction()
    {
    	        
        return array();
    }
}
