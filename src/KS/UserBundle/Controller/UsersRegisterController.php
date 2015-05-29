<?php

namespace KS\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\Controller\Annotations\NamePrefix;    
use FOS\RestBundle\View\RouteRedirectView;                
use FOS\RestBundle\View\View AS FOSView;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;         
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;

use KS\UserBundle\Entity\User;

/**
 *
 * @NamePrefix("api_v1_users_")
 */
class UsersRegisterController extends FOSRestController
{


    /**
     * Creates a new User entity.
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", requirements="^\w+", default="", description="Username")
     * @RequestParam(name="email", requirements="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$", default="", description="Email")
     * @RequestParam(name="password", requirements="\w+", default="", description="Mot de passe")
     *
     * @Post("/users/register")
     *
     * @return FOSView
     */
    public function registerUsersAction(ParamFetcher $params, Request $request)
    {
    	$view = FOSView::create();

        $user = new User();
        $user->setUsername($params->get('username'));
        $user->setEmail($params->get('email'));
        $user->setPlainPassword($params->get('password'));
        $user->setRoles(array('ROLE_USER'));
        $user->setScopes(array('public'));

        $validator = $this->get('validator');

        $errors = $validator->validate($user, array('Registration'));

        if (count($errors) == 0) {
            try {

                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($user);
                $view->setStatusCode(200)->setData($user);

                $url = $this->get('router')->generate('api_v1_users_delete_force_user', array(
                        'username' => $user->getUsername(),
                        'token'    => $user->getTokenForceDelete(),
                        '_format'  => 'json'
                    ),
                    true
                );

                $nameFile = 'register.en.txt.twig';
                $subject  = 'New account on Komunity Store';
                if($request->headers->get('language')){
                    switch ($request->headers->get('language')) {
                        case 'en':
                            $nameFile = 'register.en.txt.twig';
                            $subject  = 'New account on Komunity Store';
                            break;
                        case 'fr':
                            $nameFile = 'register.fr.txt.twig';
                            $subject  = 'Nouveau compte sur Komunity Store';
                            break;
                        default:
                            $nameFile = 'register.en.txt.twig';
                            $subject  = 'New account on Komunity Store';
                            break;
                    }
                }

                $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom('contact@komunitystore.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->render('KSUserBundle:Email:' . $nameFile, array(
                                'url_delete' => $url
                            )
                        )
                    );

                $this->container->get('mailer')->send($message);

            } catch (\Exception $e) {
                $error = array(
                    'error' => 'user_already_exist',
                    'error_description' => 'Delete user in work'
                );
                $view->setStatusCode(400)->setData($error);
            }
        
        } 
        else{
            $error = array(
                'error' => 'user_already_exist',
                'error_description' => $errors[0]->getMessage()
            );
            $view->setStatusCode(400)->setData($error);
        }

        return $this->handleView($view);

    }

    /**
     * Force delete User entity.
     *
     * @RequestParam(name="token", default="", description="Force token delete")
     * @RequestParam(name="username", default="^\w+", description="Username")
     *
     * @Get("/users/delete/{username}/{token}")
     *
     */
    public function deleteForceUserAction($username, $token){

        $view = FOSView::create();

        $deleteUser = $this->container->get('ksuser.handler.user')->forceDelete(
            $username, $token 
        );
        
        if(null !== $deleteUser){
            $view = $this->view($deleteUser, 202);
        }
        else{
            $error = array(
                'error' => 'user_already_delete',
                'error_description' => $this->get('translator')->trans('user_already_delete')
            );
            $view->setStatusCode(404,$deleteUser);
        }

        return $this->handleView($view);
    }  

}
