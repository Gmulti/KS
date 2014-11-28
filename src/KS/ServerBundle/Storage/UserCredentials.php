<?php

namespace KS\ServerBundle\Storage;

use OAuth2\Storage\UserCredentialsInterface;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use KS\ServerBundle\User\OAuth2UserInterface;
use KS\ServerBundle\User\AdvancedOAuth2UserInterface;

class UserCredentials implements UserCredentialsInterface
{
    private $mongo;
    private $up;
    private $encoderFactory;

    public function __construct($mongo, UserProviderInterface $userProvider, EncoderFactoryInterface $encoderFactory)
    {
        $this->mongo = $mongo;
        $this->up = $userProvider;
        $this->encoderFactory = $encoderFactory;
    }

    public function checkUserCredentials($username, $password)
    {
        // Load user by username
        try {
            $user = $this->up->loadUserByUsername($username);
        } catch (\Symfony\Component\Security\Core\Exception\UsernameNotFoundException $e) {
            return false;
        }

        // Do extra checks if implementing the AdvancedUserInterface
        if ($user instanceof AdvancedUserInterface) {
            if ($user->isAccountNonExpired() === false) return false;
            if ($user->isAccountNonLocked() === false) return false;
            if ($user->isCredentialsNonExpired() === false) return false;
            if ($user->isEnabled() === false) return false;
        }

        // Check password
        if ($this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
            return true;
        }

        return false;
    }

    public function getUserDetails($username)
    {
        // Load user by username
        try {
            $user = $this->up->loadUserByUsername($username);
        } catch (\Symfony\Component\Security\Core\Exception\UsernameNotFoundException $e) {
            return false;
        }

        // If user implements OAuth2UserInterface or AdvancedOAuth2UserInterface
        // then we can get the scopes, score!
        if ($user instanceof OAuth2UserInterface || $user instanceof AdvancedOAuth2UserInterface) {
            $scope = $user->getScope();
        } else {
            $scope = null;
        }

        return array(
            'user_id' => $user->getUsername(),
            'scope' => $scope
        );
    }
}
