<?php

namespace KS\UserBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;
use KS\UserBundle\Entity\User;


class UserTokenUtils
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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

        return $this->em
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByToken($token);
    }

    public function getTokenByUsername($username){
        $data = $this->em
            ->getRepository('KSServerBundle:AccessToken')
            ->findOneByUserId($username);

        return $data;
    }

    public function getUsernameByTokenFromRequest(Request $request){
        return $this->getAccessTokenByTokenRequest($request)->getUserId();
    }

    public function isAccessToRequest(Request $request, User $user){

        $accessToken = $this->getAccessTokenByTokenRequest($request);

        return ($accessToken->getUserId() === $user->getUsername() ) ? true : false;
       
    }

}