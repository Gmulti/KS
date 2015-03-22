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
    	
       
//     	$user = new Client();
//     	$user->addScope($scope);
//     	$dm =$this->get('doctrine_mongodb')->getManager();
//     	$dm->persist($user);
// $dm->flush();
       // $product = $this->get('doctrine_mongodb')
       //  ->getRepository('KomunityStoreServerBundle:Client')
       //  ->find("545fd8acc729ea5a080041aa");
       //  var_dump($product);

       //  die();
        
        return array();
    }
}
