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
use KS\UserBundle\Entity\User;
use KS\DealBundle\Form\Type\DealType;
use KS\DealBundle\Models\LikeDealManyType;
use KS\DealBundle\Models\ShareDealManyType;
use KS\DealBundle\Models\ManyEntityInterface;
use KS\DealBundle\Models\ManyTypeInterface;
use KS\MediaBundle\Entity\Media;
use KS\MediaBundle\Utils\ApiUploadedFile;


/**
 *
 * @NamePrefix("api_v1_deals_")
 */
class DealsController extends RestController
{
    
    /**
     * Return deals list
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset deals")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Limit deals")
     * @QueryParam(name="start_price", requirements="\d+", description="Price start deals")
     * @QueryParam(name="end_price", requirements="\d+", description="Price end deals")
     * @QueryParam(name="lat", description="Latitude")
     * @QueryParam(name="lng", description="Longitude")
     * @QueryParam(name="distance", requirements="\d+", default="2000", description="Distance geolocalisation")
     * @QueryParam(name="title", description="Title deal")
     * @QueryParam(name="content", description="Content deal")
     * @QueryParam(name="date_offset", description="Date offset deal")
     * @QueryParam(name="user_id", description="User id who posted deal")
     *
     */
    public function getDealsAction(ParamFetcher $params, Request $request)
    {      
        $view = FOSView::create(); 

        $em = $this->getDoctrine()->getManager();
        $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
        $user = $em->getRepository('KSUserBundle:User')->findOneByUsername($username);
        
        $data = $this->getDealsWithParams($params, $user);
        
        if ($data) {
           

            foreach ($data as $key => $value) {                
                $data[$key] = $this->getAlreadyMany($value, $user, new LikeDealManyType());
                $data[$key] = $this->getAlreadyMany($value, $user, new ShareDealManyType());
            }

            $view = $this->view($data, 200);
            $view->setData($data);
        }
        else{

            $errors = array(
                'error' => 'deals_not_found',
                'error_description' => $this->get('translator')->trans('deals_not_found'),
            );

            $view = $this->view($errors, 404);
        }

        return $this->handleView($view);
    }

    /**
     * Return a Deal
     *
     * @ParamConverter("deal")
     */
    public function getDealAction(Deal $deal, Request $request){

        $view = FOSView::create();

        if ($deal) {
            $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('KSUserBundle:User')->findOneByUsername($username);
            $deal = $this->getAlreadyMany($deal, $user, new LikeDealManyType());
            $deal = $this->getAlreadyMany($deal, $user, new ShareDealManyType());

            $view = $this->view($deal, 200);
        }
        else{
            $error = array(
                'error' => 'deal_not_found',
                'error_description' => $this->get('translator')->trans('deal_not_found')
            );
            $view = $this->view($error, 404);
        }

        return $this->handleView($view);
    }

  
    /**
     * Create a Deal
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
            $error = array(
                'error' => 'no_access_deal', 
                'error_description' => $this->get('translator')->trans('no_access_deal')
            );
           
            $view = $this->view($error, 401);
        }

        return $this->handleView($view);      

    }



    /**
     * Edit a Deal
     * @ParamConverter("deal")
     *
     */
    public function putDealAction(Request $request, Deal $deal){
    
        $view = FOSView::create();

        if($this->container->get('ksuser.utils.usertoken')->isAccessToRequest($request, $deal->getUser())){
            $updateDeal = $this->container->get('ksdeal.handler.deal')->put(
                $deal, $request 
            );

            if(null !== $updateDeal){
                $view = $this->view($updateDeal, 200);

            }
            else{
                $error = array(
                    'error' => 'error_put_deal', 
                    'error_description' => $this->get('translator')->trans('error_data')
                );

                $view = $this->view($error, 404);
            }
        }
        else{
            $error = array(
                'error' => 'no_access_deal', 
                'error_description' => $this->get('translator')->trans('no_access_deal')
            );
           
            $view = $this->view($error, 401);
        }

        return $this->handleView($view);   
    }

    /**
     * Delete a Deal
     * @ParamConverter("deal")
     *
     */
    public function deleteDealAction(Deal $deal, Request $request){
    
        $view = FOSView::create();

        if($this->container->get('ksuser.utils.usertoken')->isAccessToRequest($request, $deal->getUser())){
            $deleteDeal = $this->container->get('ksdeal.handler.deal')->delete(
                $deal 
            );
            
            if(null !== $deleteDeal){
                 $view = $this->view($deleteDeal, 202);

            }
            else{
                $view = $this->view($deleteDeal, 404);
            }
        }
        else{
            $view->setStatusCode(401,array(
                    'error' => 'no_access_deal', 
                    'error_description' => $this->get('translator')->trans('no_access_deal')
                )
            );
        }

        return $this->handleView($view);   
    }

   


    private function getDealsWithParams(ParamFetcher $params, User $user){

        $offset = $params->get('offset');
        $limit = $params->get('limit');
        if($limit > 30){
            $limit = 30;
        }

        $options = array();

        if($params->get('start_price') !== null){
            $options['start_price'] = $params->get('start_price');   
        }
       
        if ($params->get('end_price') !== null) {
            $options['end_price'] = $params->get('end_price');
        }

        if ($params->get('lat') !== null && $params->get('lng') !== null) {
            $options['lat'] = $params->get('lat');
            $options['lng'] = $params->get('lng');
            $distance = $params->get('distance');

            if ($distance!== null && $distance < 50000) {
                $options['distance'] = $params->get('distance');
            }
        }

        if ($params->get('content') !== null) {
            $options['content'] = $params->get('content');
        }
        
        if ($params->get('title') !== null) {
            $options['title'] = $params->get('title');
        }
        
        if(null != $params->get('date_offset')){
            $options['date_offset'] = $params->get('date_offset');
        }

        if(null != $params->get('user_id')){
            $options['user_id'] = $params->get('user_id');
        }


        if(count($options) === 0){
            $options = array(
                "user" => $user
            );
        }

        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSDealBundle:Deal')
            ->getDealsWithOptions($options, $limit, $offset);

        return $data;
    }


    private function getAlreadyMany(ManyEntityInterface $entityMany, User $user, ManyTypeInterface $typeMany){
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('KSDealBundle:Deal')->getManyByUser($entityMany, $user, $typeMany);

        if ($typeMany instanceOf LikeDealManyType) {
            if(null === $result){
                $entityMany->setAlreadyLike(false);
            }
            else{
                $entityMany->setAlreadyLike(true);
            }
        }
        elseif ($typeMany instanceOf ShareDealManyType) {
            if(null === $result){
                $entityMany->setAlreadyShare(false);
            }
            else{
                $entityMany->setAlreadyShare(true);
            }
        }

        return $entityMany;
    }

}
