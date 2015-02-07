<?php

namespace KS\MediaBundle\Controller;

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
use KS\MediaBundle\Entity\Media;

/**
 *
 * @NamePrefix("api_v1_medias_")
 */
class MediasController extends RestController
{
    /**
     * Render media
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("media")
     * @QueryParam(name="thumbnail", requirements="([a-z]*[_][a-z]*)([a-z]*)", default="full", description="Limit posts")
     */
    public function getMediasUrlAction(Media $media, ParamFetcher $params)
    {
        $view = FOSView::create();

        $thumbnail =  $params->get('thumbnail');

        if ($media) {

            if('full' === $thumbnail){
                 $imagePath = $media->getWebPath();
            }
            else{
                $imagemanagerResponse = $this->container
                    ->get('liip_imagine.controller')
                    ->filterAction($this->getRequest(), $media->getPathImagine(), $thumbnail);
                
                $imagePath = $imagemanagerResponse->headers->get('location');
            }
            
            $view = $this->view($imagePath, 200);
        }
        else{
            $error = array(
                'error' => 'not_found',
                'error_description' => 'Media not found'
            );
            $view->setStatusCode(404, $error);
        }

        return $this->handleView($view);
    }

    /**
     * Get a media
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("media")
     */
    public function getMediaAction(Media $media){

         $view = FOSView::create();

        if ($media) {
            $view = $this->view($media, 200);
        }
        else{
            $error = array(
                'error' => 'Not found',
                'error_description' => 'Media not found'
            );
            $view->setStatusCode(404, $error);
        }

        return $this->handleView($view);
    }

     private function getMediaWithParams(ParamFetcher $params){

        // $limit = $params->get('limit');

        // $data = $this->get('doctrine_mongodb')
        //     ->getRepository('KSPostBundle:Post')
        //     ->findBy(array(), array('updated' => 'DESC'), $limit, $offset);

        return $data;
    }
}
