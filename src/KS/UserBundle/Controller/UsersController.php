<?php

namespace KS\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\Controller\Annotations\NamePrefix;    
use FOS\RestBundle\View\RouteRedirectView;                
use FOS\RestBundle\View\View AS FOSView;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;         
use JMS\SecurityExtraBundle\Annotation\Secure;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController as RestController;

use KS\UserBundle\Entity\User;
use KS\UserBundle\Models\FollowUserManyType;
use KS\DealBundle\Models\ManyEntityInterface;
use KS\DealBundle\Models\ManyTypeInterface;

/**
 *
 * @NamePrefix("api_v1_users_")
 */
class UsersController extends RestController
{


    /**
     * Return users list
     *
     * @return FOSView
     * @Route(requirements={"_format"="json|xml"})
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset users")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Limit users")
     * @QueryParam(name="username", description="Username")
     *
     */
	public function getUsersAction(ParamFetcher $params, Request $request)
    {	
        $view = FOSView::create();

        $data = $this->getUsersWithOptions($params);
        var_dump("ok");
        die();
        if ($data) {
            $em = $this->getDoctrine()->getManager();
            $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);
            $user = $em->getRepository('KSUserBundle:User')->findOneByUsername($username);

            foreach ($data as $key => $value) {                
                $data[$key] = $this->getAlreadyMany($value, $user, new FollowUserManyType());
            }
        	$view = $this->view($data, 200);
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     * Return a user
     *
     * @Route(requirements={"_format"="json|xml"})
     * @ParamConverter("user", options={"repository_method": "findByIdOrUsername" })
     */
    public function getUserAction(User $user, Request $request){

       
        $view = FOSView::create();

        if ($user) {

            $username = $this->container->get('ksuser.utils.usertoken')->getUsernameByTokenFromRequest($request);

            $em = $this->getDoctrine()->getManager();
            $userRequest = $em->getRepository('KSUserBundle:User')->findOneByUsername($username);
            $user = $this->getAlreadyMany($user, $userRequest, new FollowUserManyType());

            $view = $this->view($user, 200);
        }
        else{
            $error = array(
                'error' => 'user_not_found',
                'error_description' => $this->get('translator')->trans('user_not_found')
            );
            $view->setStatusCode(404, $error);
        }

        return $this->handleView($view);

    }

    /**
     * Edit a user
     *
     * @return FOSView
     * @ParamConverter("user", options={"repository_method": "findByIdOrUsername" })
     *
     */
    public function putUserAction(Request $request, User $user){
    
        $view = FOSView::create();

        if($this->container->get('ksuser.utils.usertoken')->isAccessToRequest($request, $user)){
            $updateUser = $this->container->get('ksuser.handler.user')->put(
                $user, $request 
            );

            if(null !== $updateUser){
                $view = $this->view($updateUser, 200);

            }
            else{
                $error = array(
                    'error' => 'error_data', 
                    'error_description' =>  $this->get('translator')->trans('error_data')
                );

                $view = $this->view($error, 404);
            }   
        }
        else{
            $error = array(
                'error' => 'put_no_access_user', 
                'error_description' => $this->get('translator')->trans('put_no_access_user')
            );

            $view = $this->view($error, 401);
        }

        return $this->handleView($view);   
    }

    /**
     * Post user profile Image
     *
     * @return FOSView
     * @ParamConverter("user", options={"repository_method": "findByIdOrUsername" })
     *
     */
    public function postUserImageAction(Request $request, User $user){
    
        $view = FOSView::create();

        if($this->container->get('ksuser.utils.usertoken')->isAccessToRequest($request, $user)){
            $updateUser = $this->container->get('ksuser.handler.user')->postImage(
                $user, $request, true
            );

            if(null !== $updateUser){
                $view = $this->view($updateUser, 200);

            }
            else{
                $error = array(
                    'error' => 'error_data', 
                    'error_description' =>  $this->get('translator')->trans('error_data')
                );

                $view = $this->view($error, 404);
            }   
        }
        else{
            $error = array(
                'error' => 'put_no_access_user', 
                'error_description' => $this->get('translator')->trans('put_no_access_user')
            );

            $view = $this->view($error, 401);
        }

        return $this->handleView($view);   
    }

    /**
     * @Route(requirements={"_format"="json|xml"})
     * @ParamConverter("user", options={"repository_method": "findByIdOrUsername" })
     */
    public function deleteUserAction(User $user)
    {
        $view = FOSView::create();

        $deleteUser = $this->container->get('ksuser.handler.user')->delete(
            $user 
        );
        
        if(null !== $deleteUser){
             $view = $this->view($deleteUser, 202);

        }
        else{
            $view->setStatusCode(404,$deleteUser);
        }

        return $this->handleView($view);   


    } // "delete_user"   [DELETE] /users/{slug}

    /**
     * Get user's roles
     *
     * @Route(requirements={"_format"="json|xml"})
     */
    public function getRoleAction($username){

        $view = FOSView::create();
        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSUserBundle:User')
            ->findOneByUsername($username);

        if ($data) {
            $view->setStatusCode(200)->setData($data->getRoles());
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     * Get username from token
     * @Route(requirements={"_format"="json|xml"})
     * @Get("/me")
     *
     */
    public function getUsernameByTokenAction(Request $request){
        $view = FOSView::create();
      
        $data = $this->container->get('ksuser.utils.usertoken')->getAccessTokenByTokenRequest($request);

        if ($data) {
            $user = $this->getDoctrine()->getManager()
                         ->getRepository('KSUserBundle:User')
                         ->findOneByUsername($data->getUserId());
            if($user){
                $view->setStatusCode(200)->setData($user);
            }
            else{
                $view->setStatusCode(200)->setData($data->getUserId());
            }
            
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     * Get roles from token
     *
     * @Route(requirements={"_format"="json|xml"})
     * @Get("/role/{token}")
     *
     */
    public function getRoleByTokenAction($token){
        $view = FOSView::create();
        
        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByToken($token);

        $user = $this->getDoctrine()->getManager()
            ->getRepository('KSUserBundle:User')
            ->findOneByToken($data->getUserId());


        if ($user) {
            $view->setStatusCode(200)->setData($user->getRoles());
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    /**
     * Delete token / logout
     *
     * @Post("/logout")
     *
     */
    public function logoutAction(){
        $view = FOSView::create();
        
        $token = $this->container->get('ksuser.utils.usertoken')->getAccessTokenFromRequest($this->getRequest());

        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByToken($token);


        if ($data) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($data);
            $em->flush();

            $view->setStatusCode(200);
        }
        else{
            $view->setStatusCode(404);
        }

        return $this->handleView($view);
    }

    private function getUsersWithOptions(ParamFetcher $params){

        $offset = $params->get('offset');
        $limit = $params->get('limit');
        if($limit > 30){
            $limit = 30;
        }

        $options = array();

        if($params->get('username')){
            $options['username'] = $params->get('username');
        }
      
        $data = $this->getDoctrine()->getManager()
            ->getRepository('KSUserBundle:User')
            ->getUsersWithOptions($options, $limit, $offset);

        return $data;
    }

    private function getAlreadyMany(ManyEntityInterface $entityMany, User $user, ManyTypeInterface $typeMany){
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('KSUserBundle:User')->getManyByUser($entityMany, $user, $typeMany);

        if ($typeMany instanceOf FollowUserManyType) {
            if(null === $result){
                $entityMany->setAlreadyFollow(false);
            }
            else{
                $entityMany->setAlreadyFollow(true);
            }
        }

        return $entityMany;
    }


    
}
