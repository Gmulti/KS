<?php

namespace KS\UserBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use KS\UserBundle\Document\User;


class UserTokenUtils
{
    protected $om;

    public function __construct(ManagerRegistry $om)
    {
        $this->om = $om;
    }

    public function getTokenFromRequest(Request $request){
        $regex = "/Bearer (.*)/";

        if (preg_match($regex, $request->headers->get('authorization'), $matches) !== 1 ) {
            return array(
                'error' => 'no_token',
                'error' => 'Token not found or is not valid'
            );
        }

        return $matches[1];
    }

    public function getAccessTokenByTokenRequest(Request $request){
        
        $token = $this->getTokenFromRequest($request);

        return $this->om
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByToken($token);
    }

    public function getTokenByUsername($username){
        $data = $this->om
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByUserId($username);

        return $data;
    }

    public function getUsernameByTokenFromRequest(Request $request){
        return $this->getAccessTokenByTokenRequest($request)->getUserId();
    }

    public function isAccessToRequest(Request $request, User $user){

        $token = $this->getTokenFromRequest($request);
        $accessToken = $this->getAccessTokenByTokenRequest($token);

        return ($accessToken->getUserId() === $user->getUsername() ) ? true : false;
       
    }

}