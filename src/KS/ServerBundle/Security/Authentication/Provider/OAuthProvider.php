<?php

// src/KomunityStore/ServerBundle/Security/Authentication/Provider/WsseProvider.php
namespace KS\ServerBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use KS\ServerBundle\Document\AccessToken;

class OAuthProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct(UserProviderInterface $userProvider, $cacheDir)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
    }

    public function authenticate(TokenInterface $token)
    {

        $user = $this->userProvider->loadUserByUsername($token->getUserId());

        if ($user && $this->validateDigest($token->getExpires())) {
            $authenticatedToken = new AccessToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The OAuth authentication failed.');
    }

    protected function validateDigest($token)
    {
        // $test = file_get_contents("http://api.komunitystore.dev/oauth/verify?access_token=" . $token);
        // var_dump($test);
        // if (time() - $expires->getTimeStamp() > 0) {
        //     return false;
        // }



        // return $digest === $expected;
        return true;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof AccessToken;
    }
}