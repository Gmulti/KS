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

use KS\PostBundle\Document\Post;
use KS\MediaBundle\Document\Media;

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
    public function getMediasRenderAction(Media $media, ParamFetcher $params)
    {
        $view = FOSView::create();

        $img = $this->get('liip_imagine.cache.manager')->getBrowserPath('erzr/test', $params->get('thumbnail'));
        var_dump($img);
        $this->container
            ->get('liip_imagine.controller')
                ->filterAction(
                    $this->container->get('request'),          // http request
                    $media->getWebPath(),      // original image you want to apply a filter to
                    $params->get('thumbnail')             // filter defined in config.yml
        );
        die();
        if ($media) {
            var_dump($media->getAbsolutePath());
            die();
        	$url = $media->getWebPath();

            $view = $this->view($url, 200);
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

        $offset = $params->get('width');
        // $limit = $params->get('limit');

        // $data = $this->get('doctrine_mongodb')
        //     ->getRepository('KSPostBundle:Post')
        //     ->findBy(array(), array('updated' => 'DESC'), $limit, $offset);

        return $data;
    }
}
