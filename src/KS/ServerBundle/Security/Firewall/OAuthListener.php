<?php 
namespace KS\ServerBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use KS\ServerBundle\Security\Document\AccessToken;

class OAuthListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, $mongo)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->mongo = $mongo;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
    
        $regex = "/Bearer (.*)/";
        if (!$request->headers->has('authorization') || 1 !== preg_match($regex, $request->headers->get('authorization'), $matches)) {
            return;
        }

        $token = $matches[1];
        $token = $this->mongo->getRepository('KSServerBundle:AccessToken')->findOneByToken($token);
        
        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->securityContext->setToken($authToken);

        } catch (AuthenticationException $failed) {

            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);

        }
    }
}