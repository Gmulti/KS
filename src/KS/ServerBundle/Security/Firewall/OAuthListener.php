<?php 
namespace KS\ServerBundle\Security\Firewall;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use OAuth2\HttpFoundationBridge\Response;
use OAuth2\ServerBundle\Entity\AccessToken;

use Doctrine\Common\Persistence\ObjectManager;

class OAuthListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, ObjectManager $om)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->em = $om;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
    
        $regex = "/Bearer (.*)/";
        if (!$request->headers->has('authorization') || 1 !== preg_match($regex, $request->headers->get('authorization'), $matches)) {
            return;
        }

        if($request->headers->has('language')){
            switch ($request->headers->get('language')) {
                case 'en':
                    $request->setLocale('en');
                    break;
                case 'fr':
                    $request->setLocale('fr');
                    break;
                default:
                    $request->setLocale('en');
                    break;
            }
        }
        else{
            $request->setLocale('en');
        }


        $token = $matches[1];

        $token = $this->em->getRepository('KSServerBundle:AccessToken')->findOneByToken($token);

        if($token === null){
            $response = new Response();
            $response->setError(404, 'token_not_exist', 'Token not found');
            $event->setResponse($response);
        }
        else{
            try {
                $authToken = $this->authenticationManager->authenticate($token);
                $this->securityContext->setToken($authToken);

            } catch (AuthenticationException $failed) {
                $response = new Response();
                $response->setError(401, 'token_expired', 'Token used has expired');
                $event->setResponse($response);

            }
        }
        
    }
}